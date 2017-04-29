#!/bin/sh

SIML='58187e75e76ab8016835f52a'
HOST='582aad36e76ab86892f3a8b8'

MAP=$1
F=$2 #$2
P=$2 #$3
A=$2 #$4

RCMD=`echo '{"num_runs":5,"mpi_procs":0,"omp_threads":0,"priority":1,"submitted_to":' "\"${HOST}\"" ',"host_parameters":null}'`
/home/oacis/oacis/bin/oacis_cli create_parameter_sets -s ${SIML} -r ${RCMD} -o "/tmp/ps-`date`-${RANDOM}.json" -i "{\"MAP\":\"${MAP}\",\"F\":\"${F}\",\"P\":\"${P}\",\"A\":\"${A}\"}"
