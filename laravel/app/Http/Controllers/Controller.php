<?php

namespace App\Http\Controllers;

use App\Jobs\IdentifyEngine;
use App\Models\Enrollment;
use Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function showConsent(Request $request)
    {
        return view('consent');
    }

    public function showForm(Request $request)
    {
        $record = null;
        if ($request->has('token')){
            $record = Enrollment::where('token', $request->token)->firstOrFail();
        }
        return view('form')->with(['record' => $record]);
    }

    public function submitForm(Request $request){
        $retryAttempts = 3;
        $attempt = 0;
        $enrollmentBuilt = false;

        while (!$enrollmentBuilt && $attempt < $retryAttempts) {
            try {
                if (!$request->has('ignore') and $request->model == ""){
                    $request->flash();
                    return back()->withErrors(["warning" => true]);
                }
                $enr = new Enrollment($request->all());
                Log::info("Enrollment instance created successfully.");

                if ($request->has('update_token')) {
                    $old_version = Enrollment::where('token', $request->update_token)->first();
                    if ($old_version) $enr->updated_from = $old_version->id;
                }
                $token = $enr->build();
                $enrollmentBuilt = true;
                Log::info("Enrollment token created successfully, redirecting to status page.");
                return redirect('/status/' . $token); // Generate hash, get short url and save, returns the token.
            } catch (\Throwable $e) {
                $attempt++;
                Log::error("Error encountered during Enrollment build: " . $e->getMessage(), [
                    'attempt' => $attempt + 1,
                    'trace' => $e->getTraceAsString()
                ]);
                if ($attempt >= $retryAttempts) {
                    Log::critical("All retry attempts failed. Redirecting to service-unavailable page.");
                    return view('error');
                }
            }
        }
    }

    public function statusPage(Request $request, $token)
    {
        $record = Enrollment::where('token', $token)->firstOrFail();
        if ($request->expectsJson()){
            if ($record->html_fingerprint){
                if ($record->testResults()->count()) {
                    if ($record->updated_from){
                        return ["status" => 5];
                    }
                    return ["status" => 4];
                }
                return ["status" => 3];
            }
            return ["status" => 2];
        }
        return view('showurl')->with([
            'short_url' => $record->url
        ]);
    }

    public function submitUpdateStatus(Request $request, $token)
    {
        $record = Enrollment::where('token', $token)->firstOrFail();
        if (!$request->has('update_status')) abort(400, 'Wrong input');
        $record->update_status = $request->update_status;
        $record->saveOrFail();
        if ($record->update_status != 'updated') return ["status" => 5];
        return ["href" => url('/enroll?token=' . $token)];
    }

    public function submitResults(Request $request, $token){
        $record = Enrollment::where('token', $token)->firstOrFail();
        if ($record->testResults()->count())
            abort(400, "Test results for this URL were already received. Unable to process new data for this entry.");
        if ($request->has('testResults')){
            foreach ($request->all()['testResults'] as $test_id => $test_result){
                $record->testResults()->create([
                    'test_id' => $test_id,
                    'outcome' => $test_result["outcome"],
                    'reason' => $test_result["reason"],
                    'duration' => $test_result["duration"]
                ]);
            }
            return ['result' => 'success'];
        }
        if ($record->api_fingerprint)
            abort(400, "Test results for this URL were already received. Unable to process new data for this entry.");

        if ($request->has('api_fingerprint')) {
            $record->api_fingerprint = $request->api_fingerprint;
            $record->html_fingerprint = $request->html_fingerprint;
            $record->save();
            IdentifyEngine::dispatch($record);
            return ['result' => 'success'];
        }

        $record->user_agent_header = $request->header('User-Agent');
        $record->user_agent_js = $request->user_agent;
        $record->save();
        return ['result' => 'success'];
    }
}
