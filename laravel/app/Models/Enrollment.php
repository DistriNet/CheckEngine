<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class Enrollment extends Model
{

    protected $fillable = ['type', 'vendor', 'model', 'year', 'version', 'notes'];

    /**
     * @throws Throwable if saving failed
     */
    public function build(): string
    {
        $this->token = hash_hmac('sha256', Str::random(40), \config('app.key'));
        $this->url = Http::withToken(env('TINI_URL_TOKEN'))
            ->retry(3, 1000)
            ->timeout(20)
            ->post("https://tini.fyi/api/v1/url/create", [
                'destination' => 'https://test.experiment.websand.eu/test?token=' . $this->token,
            ])->throw()->json('data.shortUrl');

        // Check if URL is a non-empty string
        if (empty($this->url) || !is_string($this->url)) {
            Log::error("URL generation failed: URL is empty or invalid.");
            throw new \Exception("Failed to generate a valid URL.");
        }

        $this->saveOrFail();
        return $this->token;
    }

    public function testResults(): HasMany
    {
        return $this->hasMany(TestResult::class);
    }

    public function nextVersion()
    {
        return $this->hasOne(Enrollment::class, 'updated_from');
    }

    public function previousVersion()
    {
        return $this->belongsTo(Enrollment::class, 'updated_from');
    }

    public function engineIdentifications(){
        return $this->hasMany(EngineIdentification::class);
    }
}
