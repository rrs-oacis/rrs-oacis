#!/bin/sh

#とりあえず直指定 変更できるようにする
SIML=$1 #'590463aee4dec200d962035a'
HOST='59046193315fa7ef1ceaf976'



MAP=$2
F=$3 #$2
P=$3 #$3
A=$3 #$4

RCMD=`echo '{"num_runs":5,"mpi_procs":0,"omp_threads":0,"priority":1,"submitted_to":"${HOST}","host_parameters":null}'`
#/home/oacis/oacis/bin/oacis_cli create_parameter_sets -s ${SIML} -r ${RCMD} -o "/tmp/ps-`date`-${RANDOM}.json" -i "{\"MAP\":\"${MAP}\",\"F\":\"${F}\",\"P\":\"${P}\",\"A\":\"${A}\"}"

/home/oacis/oacis/bin/oacis_cli create_parameter_sets -s 590463aee4dec200d962035a -i '{"MAP":"'${MAP}'","F":"'${F}'","P":"'${P}'","A":"'${A}'"}' -r '{"num_runs":1,"mpi_procs":0,"omp_threads":0,"priority":1,"submitted_to":${HOST},"host_parameters":null}' -o ps.json
