# rrs-oacis

A simulation manager that extended the OACIS for the RoboCupRescue Simulation.

# Run on Docker
Firstly, start up the server:
```
docker run --name rrsoacis -p 3080:3080 -p 3000:3000 -p 49138:49138/udp -dt rrsoacis/rrsoacis
```

Next, Access to **http://localhost:3080/** using your web browser.
