<?php

class get_google_image_class
{
    public $destination = '.';
    public $display = true;
    public $limit = 0;
    public $retrieved = 0;

    public function GetImage($title)
    {
        $this->retrieved = 0;
        $this->paths = [];        
        $ch = curl_init("https://www.google.es/search?q=" . urlencode($title) . "&safe=off&source=lnms&tbm=isch");

        $fp = fopen("example_homepage.txt", "w");
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        $a = 0;
        $r = "";
        $f = fopen("example_homepage.txt", "r");
        while (!feof($f)) {
            $a = $a + 1;
            $r = fgets($f);
        }
        fclose($f);
        $te = explode(
            'https://encrypted-tbn0.gstatic.com/images?q=tbn:',
            $r,
            20
        );
        $r = 1;
        $numus = $this->limit;
        $numu = 1;
        $numus += $numu;
        for ($i = $numu; $i < $numus && $i < count($te); $i++) {
            $tedd = explode('"', $te[$i], 2);
            $url = "https://encrypted-tbn0.gstatic.com/images?q=tbn:" . $tedd[0];
            $image = @file_get_contents($url);
            if (!$image)
                break;
            $this->paths[$i] = $this->destination . '/' . str_replace(' ', '_', $title) . '-' . $r . '.jpg';
            file_put_contents($this->paths[$i],$image);
            $r = $r + 1;
        }
        $this->retrieved = $r - 1;
    }
};
