<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serverName = htmlspecialchars($_POST["serverName"]);
    $betriebssystem = htmlspecialchars($_POST["betriebssystem"]);
    $version = htmlspecialchars($_POST["version"]);
    $ram = htmlspecialchars($_POST["ram"]);
    // login logic
    if(true){
        $uuid = generateUUID();
        if(file_exists('../SERVERS/' . $uuid)){
            $uuidd = generateUUID();
            if(file_exists('../SERVERS/' . $uuidd)){
                echo "ERROR FIRST UUID:" . $uuid . " SECOND UUID:" . $uuidd;
            } else {
                createServer($serverName, $version, $ram, $betriebssystem, $uuidd);
                
            }
        }else {
            createServer($serverName, $version, $ram, $betriebssystem, $uuid);
        }
    }
}

function generateUUID() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}
function createServer($serverName, $version, $ram, $betriebssystem, $uuid){
    if(mkdir('../SERVERS/' . $uuid, 0777, true)) {
        $pfad = '../SERVERS/' . $uuid . '/serverinfo.json';
        $datei = fopen($pfad, 'w') or die('Error by createing the FILE');
        $datas = json_encode([
            'serverName' => $serverName,
            'version' => $version,
            'ram' => $ram,
            'betriebssystem' => $betriebssystem,
            'uuid' => $uuid,
        ]);

        fwrite($datei, $datas);
        fclose($datei);
        if(mkdir('../SERVERS/' . $uuid . '/Server', 0777, true)) {
            echo downloadPaperMC('1.20.4');
            $betriebssystem = PHP_OS;
            if($betriebssystem == "WINNT"){
            $pfadd = '../SERVERS/' . $uuid . '/Server/start.bat';
            $datass = "java -Xmx{$ram}G -jar server.jar";
            }else{
                 $pfadd = '../SERVERS/' . $uuid . '/Server/start.sh';
                 $datass = "screen -S Minecraft java -Xms1G -Xmx{$ram}G -jar server.jar";
            }
            
            echo "Das Betriebssystem ist: $betriebssystem";
            $dateii = fopen($pfadd, 'w') or die('Error by createing the FILE');
        }
    }else {
        echo "ERROR: Unable to create server";
    }
}

function downloadPaperMCold($version = null) {
    $apiUrl = 'https://api.github.com/repos/PaperMC/Paper/releases/latest';

    // Falls eine spezifische Version angegeben ist, den API-URL entsprechend anpassen
    if ($version !== null) {
        $apiUrl = "https://api.github.com/repos/PaperMC/Paper/releases/tags/{$version}";
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'YourAppName'); // Ersetzen Sie 'YourAppName' durch einen sinnvollen User-Agent

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        die('Fehler beim Abrufen der PaperMC-Version.');
    }

    $releaseData = json_decode($response, true);

    if (isset($releaseData['assets'][0]['browser_download_url'])) {
        $downloadUrl = $releaseData['assets'][0]['browser_download_url'];
        $downloadFileName = basename($downloadUrl);

        // Hier könnten Sie den Download-Code einfügen (z.B. mit file_get_contents, fopen, etc.)
        // Beispiel: Datei herunterladen und speichern
        file_put_contents($downloadFileName, file_get_contents($downloadUrl));

        echo "PaperMC Version {$releaseData['tag_name']} wurde erfolgreich heruntergeladen.";
    } else {
        echo "Fehler beim Ermitteln des Download-Links für PaperMC.";
    }
}
function downloadPaperMC($version = null) {
    // Falls keine spezifische Version angegeben ist, den Link zur neuesten Version verwenden
    $downloadUrl = ($version !== null) ? "https://papermc.io/api/v2/projects/paper/versions/{$version}" : "https://papermc.io/api/v2/projects/paper";

    $response = file_get_contents($downloadUrl);

    if ($response === false) {
        die('Fehler beim Abrufen der PaperMC-Version.');
    }

    $data = json_decode($response, true);

    if (isset($data['builds'][0]['downloads']['paperclip']['url'])) {
        $downloadUrl = $data['builds'][0]['downloads']['paperclip']['url'];
        $downloadFileName = basename($downloadUrl);

        // Datei herunterladen und speichern
        file_put_contents($downloadFileName, file_get_contents($downloadUrl));

        return "PaperMC Version {$data['version']} wurde erfolgreich heruntergeladen.";
    } else {
        return "Fehler beim Ermitteln des Download-Links für PaperMC.";
    }
}

// Beispielaufruf: downloadPaperMC(); // ohne Argumente für die neueste Version
// Beispielaufruf: downloadPaperMC('1.17.1'); // mit einer spezifischen Version
function downloadPaperMCmain($version){
    $builds = "https://api.papermc.io/v2/projects/paper/versions/{$version}/builds/";

}

?>

