import csv
import json
import math
import os
import pathlib


def version_str_to_int(version_str):
    """
    Converts semantic version strings to an int in a reproducible way, creating a total order on versions.
    3 DIGITS MAXIMUM PER VERSION COMPONENT, MAX 3 COMPONENTS

    Examples: 12.1.193 -> 120100193
              0.0.1    -> 100
              129      -> 129000000
              5.3      -> 500300000
    """
    multiplier = 1000 * 1000
    res = 0
    for component in version_str.split('.'):
        res += int(component) * multiplier
        multiplier /= 1000
    return res


def version_int_to_str(version_int):
    """
    Inverse of version_str_to_int, removing trailing .0.0 if applicable
    """
    if math.isinf(version_int):
        return 'inf'
    if not version_int:
        return '0'
    version_str = str(int(version_int))
    res = (
            str(int(version_str[:-6] if version_int > 100000 else "0")) + '.' +
            str(int(version_str[-6:-3])) + '.' +
            str(int(version_str[-3:]))
           )

    return res.split('.')[0] if res.endswith('.0.0') else res


def create_browser_version_table():
    """
    Create the datastructure with all browser versions from the browser json files
    """
    table = {}
    folder = pathlib.Path(os.getcwd() + '/browsers/')
    json_files = list(folder.glob("*.json"))
    for f1 in json_files:
        with open(f1, 'r') as infile:
            data = json.load(infile)["browsers"]
            name = list(data.keys())[0]
            data = data[name]
            version_dict = {}
            for release, release_data in data["releases"].items():
                engine_version = None
                if "engine" in release_data and "engine_version" in release_data:
                    engine_version = release_data["engine"] + '_' + release_data["engine_version"]
                version_dict[version_str_to_int(release)] = {
                    'vote_for': 0,
                    'vote_against': 0,
                    'engine_version':  engine_version,
                    'release_date': release_data["release_date"] if "release_date" in release_data else 'UNKNOWN'
                }
            table[name] = {
                "upstream": data.get("upstream", None),
                "excluded": False,
                "versions": version_dict
            }

    return table


def fingerprint(fingerprint, compat_table, result_table, use_attributes: bool = False):
    """
    Compare the sampled compatibility data in fingerprint with the known compatibility data in compat_table to calculate
    the similarity between the sampled browser and all the browser versions in the known compatibility data.
    :param fingerprint: The sampled features
    :param compat_table: The MDN compatibility data to match against
    :param result_table: The structure of the table with the up/downvote results
    :param use_attributes: Whether to allow comparision of attributes of html elements, in the format element_attribute
    :return: The result table input parameter but with up/downvotes casted for all the features sampled in fingerprint
    """
    for element, supported in fingerprint.items():
        # Parse attributes if applicable
        attribute = None
        if use_attributes and '_' in element:
            element, attribute = element.split('_')
        element = compat_table[element]
        if attribute:
            element = element[attribute]

        compat = element['__compat']
        status = compat['status']
        if status['deprecated'] or status['experimental']:
            continue
        browser_support = compat['support']

        for browser in result_table.keys():
            if result_table[browser]["excluded"]:
                continue
            if browser not in browser_support:
                if supported:
                    result_table[browser]["excluded"] = True  # TODO: what do we want to do with excluded values?
                else:
                    for version in result_table[browser]["versions"].keys():
                        result_table[browser]["versions"][version]["vote_for"] += 50  # TODO: decide a non-arbitrary value
                continue
            compat_data = browser_support[browser]

            # Get possibly mirrored data
            browser_to_check = browser
            while compat_data == 'mirror':
                browser_to_check = result_table[browser_to_check]["upstream"]
                compat_data = browser_support[browser_to_check]

            if 'prefix' in compat_data or 'alternative_name' in compat_data or 'flags' in compat_data:  # TODO: use this information in the frontend tests
                continue

            if 'version_added' in compat_data:
                version_added = compat_data['version_added']
                if version_added is False:
                    if supported:
                        result_table[browser]["excluded"] = True
                    continue
                if version_added is None or version_added is True or version_added == 'preview':
                    continue
                if version_added.startswith('≤'):  # TODO: maybe don't cast votes for the uncertain part of the range
                    version_added = version_added[1:]
                version_added_int = version_str_to_int(version_added)
                version_removed_int = math.inf
                if 'version_removed' in compat_data:
                    version_removed = compat_data['version_removed']
                    if not (version_removed is None or version_removed is True or version_removed is False):
                        version_removed_int = version_str_to_int(version_removed)
                if supported:
                    for version in result_table[browser]["versions"].keys():
                        if version < version_added_int or version >= version_removed_int:
                            result_table[browser]["versions"][version]["vote_against"] += 1
                        else:
                            result_table[browser]["versions"][version]["vote_for"] += 1
                else:
                    for version in result_table[browser]["versions"].keys():
                        if version >= version_added_int and version < version_removed_int:
                            result_table[browser]["versions"][version]["vote_against"] += 1
                        else:
                            result_table[browser]["versions"][version]["vote_for"] += 1

    return result_table


def fingerprint_html(filename, table):
    # Build compatibility table from MDN json data folder
    elements = {}
    folder = pathlib.Path(os.getcwd() + '/elements/')
    json_files = list(folder.glob("*.json"))
    for f1 in json_files:
        with open(f1, 'r') as infile:
            elements.update(json.load(infile)["html"]["elements"])

    # Read fingerprint data
    with open(filename, 'r') as infile:
        fp = json.load(infile)

    return fingerprint(fp, elements, table, use_attributes=True)


def fingerprint_api(filename, table):
    # Build compatibility table from MDN json data folder
    apis = {}
    folder = pathlib.Path(os.getcwd() + '/api/')
    json_files = list(folder.glob("*.json"))
    for f1 in json_files:
        with open(f1, 'r') as infile:
            apis.update(json.load(infile)["api"])

    # Read fingerprint data
    with open(filename, 'r') as infile:
        fp = json.load(infile)

    return fingerprint(fp, apis, table)


def build_ranking(table):
    ranking = []
    for browser, browser_data in table.items():
        if not browser_data["excluded"]:
            for version, value in browser_data["versions"].items():
                vote_for = value['vote_for']
                vote_against = value['vote_against']
                ranking.append({
                    'version': browser + '_' + version_int_to_str(version),
                    'release_date': value['release_date'],
                    'votes_for': vote_for,
                    'votes_against': vote_against,
                    'votes_net': vote_for - vote_against,
                    'votes_total': vote_for + vote_against
                })

    return sorted(ranking, key=lambda k: k['votes_net'], reverse=True)


def make_engine_ranking(table):
    engine_ranking = {}
    for browser_data in table.values():
        if not browser_data["excluded"]:
            for version_data in browser_data["versions"].values():
                if version_data["engine_version"]:
                    version = version_data["engine_version"]
                    if version not in engine_ranking:
                        engine_ranking[version] = {
                            'version': version,
                            'release_date': version_data['release_date'],
                            'votes_for': 0,
                            'votes_against': 0,
                            'votes_net': 0,
                            'votes_total': 0
                        }
                    engine_ranking[version]["votes_for"] += version_data["vote_for"]
                    engine_ranking[version]["votes_against"] += version_data["vote_against"]
                    engine_ranking[version]["votes_net"] += version_data["vote_for"] - version_data["vote_against"]
                    engine_ranking[version]["votes_total"] += version_data["vote_for"] + version_data["vote_against"]

    return sorted(engine_ranking.values(), key=lambda k: k['votes_net'], reverse=True)


def write_csv(data, filename):
    with open(filename, 'w') as csvfile:
        # creating a csv dict writer object
        writer = csv.DictWriter(csvfile, fieldnames=[
            'version', 'votes_for', 'votes_against', 'votes_net', 'votes_total', 'release_date']
        )
        writer.writeheader()
        writer.writerows(data)


def print_top(data, n=20):
    i = 1
    for item in data:
        print(f"{i}. {item['version']}: {item['votes_net']} ({item['release_date']})")
        i += 1
        if i > n:
            return


version_table = create_browser_version_table()
version_table = fingerprint_html('elements.json', version_table)
version_table = fingerprint_api('api.json', version_table)
browser_ranking = build_ranking(version_table)
engine_ranking = make_engine_ranking(version_table)
write_csv(browser_ranking, 'browser.csv')
write_csv(engine_ranking, 'engine.csv')
