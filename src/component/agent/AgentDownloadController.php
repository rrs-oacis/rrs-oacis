<?php
namespace rrsoacis\component\agent;

use rrsoacis\component\common\AbstractController;
use rrsoacis\manager\AgentManager;
use rrsoacis\manager\component\Agent;

class AgentDownloadController extends AbstractController
{
	public function anyIndex($param = null)
	{
		$agentName = $param;

		AgentManager::updateManifest($agentName);
		$output = AgentManager::getZipBinary($agentName);
		$zipFileName = 'agent_'.$agentName.'.zip';

		// stream output
		header('Content-Type: application/zip; name="' . $zipFileName . '"');
		header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
		header('Content-Length: ' . (strlen(bin2hex($output))/2));
		echo $output;
	}
}
