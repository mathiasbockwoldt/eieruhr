Eieruhr
-------

Shall help to concentrate in the office by synchronizing working times.

`index.html` is an example client. It runs with HTML, CSS, and JavaScript, querying every 40 seconds by AJAX and displaying the results.

`eieruhr.php` is the server. It works with PHP and delivers information upon request. It may be called with `?bus=n` and will send the next `n` busses leaving from UiT in addition to the other information. `n` must be between 1 and 20.

The response from the server is `status;time_till_change;businfo`. `status` is either `p` (pause) or `w` (working) or `u` (unknown). `time_till_change` is the time until the next status change in minutes. `businfo` is optional. It contains `time:number:direction`. `time` is the time until the bus leaves in minutes. `number` is the bus number and `direction` the coded direction; either `S` (Sentrum) or `G` (Giæverbukta). The bus info may come in the requested number of blocks.
