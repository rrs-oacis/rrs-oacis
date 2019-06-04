# RRS-OACIS

An experiment management software which extended OACIS for the RoboCupRescue Simulation.

# Run on Docker
1. Start up the RRS-OACIS server:
```
docker run --name rrsoacis -p 3080:3080 -p 3000:3000 -p 49138:49138/udp -dt rrsoacis/rrsoacis
```

2. Access to **http://localhost:3080/** using your web browser.
