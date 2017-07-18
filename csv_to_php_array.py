import sys

if len(sys.argv) < 2:
	print('Please give the csv filename as argument')
	sys.exit()

res = ["0 => 'p'"]

last = None

with open(sys.argv[1]) as f:
	for l in f:
		line = l.rstrip().split('#')[0]
		if not line:
			continue

		hour, minute, mode = line.split()
		time = int(hour)*3600 + int(minute)*60

		if last is None:
			last = "{} => 'w'".format(time + 86400)

		res.append("{} => '{}'".format(time, mode))

res.append(last)

print(', '.join(res))
