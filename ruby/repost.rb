
if ARGV.size > 0 then
	run = Run.find(ARGV[0])
	ps = run.parameter_set;
	host_group = HostGroup.where(name: "RRS-OACIS").first;
	run.discard;
	ps.find_or_create_runs_upto(1, host_group: host_group);
end
