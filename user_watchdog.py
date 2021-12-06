import os
import time
from datetime import datetime
import json
import pytz
now = datetime.now()
latest_file = '/pixelmon/logs/latest.log'
logout_log = '/pixelmon/logs/logout.json'

# its late and im going to bed, but heres what i should do:
# grab the entire latest.log file and store it in ram as a string
# when the latest.log file changes, compare the old vs new and grab any "left the game" mentions
# if there are no mentions, set old to new and do nothing, if there are "left the game" mentions, 
# then take the time/username and write it out to the logout.json and set the old to the new, wait until it changes again
# this is perfectly fine because the latest.log file is pretty small (usually in the 40-60KB range)

with open(latest_file) as f:
    prev_log = f.readlines()

while True:
    with open(latest_file) as f:
        cur_log = f.readlines()

    # compare them every 5 seconds
    if (cur_log != prev_log):
        # find whats different
        new_logs = cur_log.copy()
        for element in prev_log:
            if element in cur_log:
                new_logs.remove(element)
        for element in new_logs:
            if 'left the game' in element:
                #timestamp = element[1:9]
                #datetime.strptime(timestamp, "%H:%M:%S")
                b, k, a = element.partition("Server]: ")
                username, k, a = a.partition(" left the game")
                with open(logout_log, "r") as jsonFile:
                    data = json.load(jsonFile)
                dt_us_central = datetime.now(pytz.timezone('US/Central'))
                data[username] = dt_us_central.strftime("%Y:%m:%d %H:%M:%S %Z %z")
                with open(logout_log, "w") as jsonFile:
                    json.dump(data, jsonFile)
    prev_log = cur_log
    time.sleep(5)

