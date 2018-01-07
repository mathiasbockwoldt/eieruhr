Eieruhr
-------

Shall help to concentrate in the office by synchronizing working times.

`index.html` is an example client. It runs with HTML, CSS, and JavaScript, querying every 40 seconds by AJAX and displaying the results.

`eieruhr.php` is the server. It works with PHP and delivers information upon request. It may be called with `?bus` and will send the next busses leaving from UiT in addition to the other information. The number of busses depends on the Tromskortet API.

The response from the server is `status;time_till_change;next_status;next_time_till_change;businfosouth;NORTH;businfonorth`. `status` and `next_status` are either `p` (pause) or `w` (working) or `u` (unknown). `time_till_change` (and `next_time_till_change`) is the time until the next status change in seconds. `businfosouth` and `businfonorth` contain `time|number|direction`. `time` is the time when the bus is leaving in the format `hh:mm:ss`. `number` is the bus number and `direction` direction. South and north bound busses are separated by the signal word `NORTH`.
