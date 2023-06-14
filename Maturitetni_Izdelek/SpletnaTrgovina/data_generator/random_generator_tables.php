<?php
    header('Content-Type: text/html; charset=utf-8');
    
    $table_podnozje = [
        "Intel" => ["LGA1151", "LGA2066"],
        "AMD" => ["AM4", "TR4"]
    ];
    $table_cpu_max_hitrost = [3.5, 5.2];
    $table_ram_tip = ["DDR4", "DDR5"];
    $table_ram_max_hitrost = [
        "DDR3" => [800, 1200, 1300, 1520, 1650, 1900, 2000, 2133],
        "DDR4" => [2400, 2666, 2933, 3000, 3200, 3600, 4000, 4400],
        "DDR5" => [4800, 5000, 5400, 5600, 6000, 6200, 7000, 7600, 8400]
    ];
    $table_ram_tdp = [
        "DDR3" => ["tdp" => 2, "per" => 8],
        "DDR4" => ["tdp" => 3, "per" => 8],
        "DDR5" => ["tdp" => 5, "per" => 8]
    ];

    $table_cpu_tdp = [70, 300, "mod" => 10];
    $table_ram_max_velikost_gb = [64, 128, 256, 512];
    $table_ram_min_velikost_gb = [4, 8, 16];
    $table_ram_max_st_plosc = [2, 4, 8];
    $table_ram_st_ploscic = [1, 2, 4];
    $table_ram_max_velikost_gb_plosce = [8, 16, 32, 64];

    $table_pcle = [
        "4x16" => [1, 2],
        "4x8" => [1, 2],
        "3x16" => [0, 2]
    ];

    $table_disk_konektor = [
        "sata6" => [1, 6],
        "m.2" => [0, 3],
        "m.2_2" => [0, 3],
    ];

    $table_usb = [
        "2" => [0, 3],
        "3" => [0, 2],
        "3_1_gen1" => [0, 2],
        "3_1_gen2" => [0, 2],
        "3_2" => [0, 2],
    ];

    $table_ah = [
        "audio_in" => [1, 3],
        "audio_out" => [1, 3]
    ];

    $table_pin_konektor = [
        "cpu_fan_header" => [1, 1],
        "water_cooling_cpu_fan_header" => [0, 1],
        "sys_fan_header_3pin" => [2, 5],
        "sys_fan_header_4pin" => [2, 5],
        "rgb" => [0, 6],
        "2" => [0, 2],
        "3" => [0, 2],
        "3_1_gen1" => [0, 2],
        "3_1_gen2" => [0, 2],
        "3_2" => [0, 2],
    ];

    $table_moab_tip = ["Mini-ITX", "MicroATX", "ATX", "EATX"];
    $table_gpu_sli = [0, 1];

    $table_gpu_dolzina = [150, 320];
    $table_gpu_sirina = [100, 180];
    $table_gpu_visina = [50, 110];

    $table_gpu_memory = [2, 4, 8, 10, 12, 16, 24];
    $table_gpu_hitrost = [1200, 2500];
    $table_gpu_cip = [
        "Nvidia" => ["GA106", "GA102", "GA107", "GA104", "TU117", "TU106"],
        "AMD" => ["Navi24", "Navi23", "Polaris20", "Navi21"]
    ];

    $table_gpu_konektor = [4, 6, 8];

    $table_gpu_poraba = [40, 350];

    $table_sys_cool_st_fan = [1, 4];
    // $table_sys_cool_


    $table_psu_konektor = [
        "atx_main_24pin" => [1, 1],
        "eps_4pin" => [0, 1],
        "eps_8pin" => [0, 1],
        "pcle_6pin" => [2, 4],
        "pcle_6_2pin" => [0, 4],
        "molex_4pin" => [2, 4],
        "sata" => [2, 6]
    ];

    $table_storage_tip = ["SSD", "HDD"];
    $table_disk_form_factor = [
        ["m.2_2", "m.2"],
        "sata6" => ["2.5", "3.5"]
    ];
    $table_disk_velikost = [
        "SSD" => [120, 250, 500, 1000, 2000, 3000, 4000],
        "HDD" => [500, 1000, 2000, 3000, 4000, 8000, 10000]
    ];
    $table_storage_hitrost_branja_pisanja = [
        "SSD" => [300, 3400],
        "HDD" => [50, 200]
    ];

    $table_storage_tdp = [
        "SSD" => [3, 8],
        "HDD" => [4, 6]
    ];

    $table_fan_size = [80, 120, 140, 180, 200];
    $table_fan_konektor = ["3pin", "4pin"];
    $table_fan_tdp = [
        80 => [1.2, 2],
        120 => [1.5, 2.8],
        140 => [2, 3.6],
        180 => [2.5, 4.4],
        200 => [3, 5.2]
    ];
    $table_fan_hitrost = [300, 1500, "mod" => 50];

    $table_cpu_cool_type = [
        "water" => 0.2,
        "air" => 0.8,
    ];

    $table_psu_watt = [500, 1500, "mod" => 50];
    $table_psu_ucinkovitost = [0.75, 0.95];
    $table_psu_tip = ["MicroATX", "ATX", "EATX"];
    $table_psu_konektor = [
        "MicroATX" => ["4" => [1, 2], "6" => [1, 2], "8" => [0, 2], "24" => [1, 1]],
        "ATX" => ["4" => [1, 3], "6" => [1, 3], "8" => [1, 3], "24" => [1, 1]],
        "EATX" => ["4" => [2, 4], "6" => [2, 4], "8" => [2, 3], "24" => [1, 1]]
    ];

    
    $table_case_tip = ["SFF", "Mini_Tower", "Mid_Tower", "Full_Tower"];
    $table_case_moab = [
        "SFF" => ["Mini-ITX"],
        "Mini_Tower" => ["Mini-ITX", "MicroATX"],
        "Mid_Tower" => ["Mini-ITX", "MicroATX", "ATX"],
        "Full_Tower" => ["Mini-ITX", "MicroATX", "ATX", "EATX"]
    ];
    $table_case_sys_fan = [
        "SFF" => [1, 3],
        "Mini_Tower" => [2, 4],
        "Mid_Tower" => [3, 7],
        "Full_Tower" => [5, 8]
    ];

    $table_case_disk = [
        "SFF" => ["2.5" => [0, 4], "3.5" => [1, 3]],
        "Mini_Tower" => ["2.5" => [1, 3], "3.5" => [3, 5]],
        "Mid_Tower" => ["2.5" => [1, 5], "3.5" => [4, 6]],
        "Full_Tower" => ["2.5" => [1, 7], "3.5" => [6, 9]]
    ];

    $table_categories = ["cpu", "moab", "ram", "gpu", "psu", "storage", "cpu_cool", "sys_cool", "case"];
    $table_categories_translate = [
        "cpu" => "Procesorji",
        "moab" => "Matične plošče",
        "ram" => "RAM",
        "gpu" => "Grafične kartice",
        "psu" => "Napajalniki",
        "storage" => "Pomnilniške naprave",
        "cpu_cool" => "Procesorsko hlajenje",
        "sys_cool" => "Sistemsko hlajenje",
        "case" => "Ohišja"
    ];

    $table_cene = [
        "cpu" => [130, 600], 
        "moab" => [70, 500], 
        "ram" => [50, 500], 
        "gpu" => [200, 2000], 
        "psu" => [50, 130], 
        "storage" => [30, 500], 
        "cpu_cool" => [40, 200], 
        "sys_cool" => [5, 20], 
        "case" => [40, 150]
    ];

    $table_proizvajalci = [
        "cpu" => ["AMD", "Intel"], 
        "moab" => ["Gigabyte", "ASUS", "MSI", "ASRock"], 
        "ram" => ["Corsair", "G.Skill", "Kingston", "Crucial", "Adata"], 
        "gpu" => ["Nvidia", "AMD", "EVGA", "ASUS", "Gigabyte", "MSI"], 
        "psu" => ["Corsair", "BeQuiet", "CoolerMaster", "EVGA", "ThermalLake"], 
        "storage" => ["Samsung", "WesternDigital", "Seagate", "Intel", "Toshiba", "Kingston"], 
        "cpu_cool" => ["Corsair", "NZXT", "Noctua", "BeQuiet", "CoolerMaster", "EK", "Deepcool"], 
        "sys_cool" => ["Noctua", "NZXT", "CoolerMaster", "BeQuiet"], 
        "case" => ["NZXT", "BeQuiet", "Corsair", "CoolerMaster", "LianLi"]
    ];

    $table_names = [
        "cpu" => [
            "AMD" => ["Ryzen 5 3600X", "Ryzen Threadripper 2950X", "Ryzen 5 2400G", "Ryzen 7 Pro 2700", "AMD Ryzen 9 5950X", "AMD Ryzen 7 5800X", "AMD Ryzen 7 5800X", "AMD Ryzen 7 5700G", "AMD Ryzen 5 5600G"], 
            "Intel" => ["Intel® Core™ i7-3820 Processor", "Intel Core i9-9900KFC", "Intel® Core™ i7-4930MX ", "Intel Core i9-12900K", "Intel Core i7-12700K", "Intel Core i5-12600K"]
        ], 
        "moab" => [
            "Gigabyte" => ["Gigabyte Z390 UD", "Gigabyte GA-A320M-S2H", "Z690 GAMING X", "B660M GAMING X", "Z690 AORUS ELITE AX DDR4", "Z690 AORUS XTREME WATERFORCE", "Z690 AERO D"], 
            "ASUS" => ["Asus Prime Z390-A", "Asus Prime A320M-K", "ROG STRIX Z590-A GAMING WIFI II", "ROG Maximus XIII Extreme Glacial", "ROG STRIX B550-E GAMING", "TUF GAMING Z690-PLUS WIFI", "PRIME Z690-A"], 
            "MSI" => ["MSI B350M Pro-VDH", "MSI Z390-A Pro", "MAG H670 TOMAHAWK WIFI DDR4", "MAG B660M BAZOOKA DDR4", "MPG Z690 FORCE WIFI", "MEG Z690 UNIFY-X", "MPG Z690 CARBON EK X"], 
            "ASRock" => ["Asrock Z370 Extreme4", "Asrock B450M Steel Legend", "W480 Creator", "X570S PG Riptide", "Z690 Steel Legend WiFi 6E", "Z690 Extreme WiFi 6E", "Fatal1ty X299 Professional Gaming i9 XE"]
        ], 
        "ram" => [
            "Corsair" => ["DOMINATOR PLATINUM RGB 32GB (2x16GB) DDR5 DRAM 6200MHz C36 Memory Kit — Black", "VENGEANCE 32GB (2x16GB) DDR5 DRAM 4800MHz C40 Memory Kit — Black", "DOMINATOR PLATINUM RGB 32GB (2 x 16GB) DDR4 DRAM 3600MHz C18 Memory Kit", "VENGEANCE LPX 16GB (2 x 8GB) DDR4 DRAM 3200MHz C16 Memory Kit - Black", "VENGEANCE RGB RT 32GB (2 x 16GB) DDR4 DRAM 3200MHz C16 Memory Kit – Black"],
            "G.Skill" => ["Trident Z5 DDR5-5600MHz CL36-36-36-76 32GB 1.20V (2x16GB)", "Trident Z5 RGB DDR5-5600MHz CL40-40-40-76 1.20V 32GB (2x16GB)", "Trident Z5 RGB DDR5-6000MHz CL36-36-36-76 1.30V 32GB (2x16GB)", "Ripjaws S5 DDR5-5600MHz CL36-36-36-76 1.20V 32GB (2x16GB)", "Ripjaws S5 DDR5-5200MHz CL36-36-36-83 1.20V 32GB (2x16GB)"], 
            "Kingston" => ["Kingston FURY Renegade DDR4 Memory", "Kingston FURY Renegade DDR4 RGB Memory", "Kingston FURY Beast DDR5 Memory", "Kingston FURY Beast DDR4 RGB Memory", "Kingston FURY Beast DDR4 Memory"], 
            "Crucial" => ["Crucial Ballistix MAX 16GB Kit (2 x 8GB) DDR4-4000 Desktop Gaming Memory (Black)", "Crucial Ballistix MAX 32GB Kit (2 x 16GB) DDR4-4000 Desktop Gaming Memory (Black)", "Crucial Ballistix MAX RGB 32GB Kit (2 x 16GB) DDR4-4000 Desktop Gaming Memory (Black)", "Crucial Ballistix 16GB Kit (2 x 8GB) DDR4-3000 Desktop Gaming Memory (Black)", "Crucial Ballistix MAX RGB 32GB Kit (2 x 16GB) DDR4-4400 Desktop Gaming Memory (Black)"], 
            "Adata" => ["DDR5 4800 U-DIMM", "DDR4 3200 U-DIMM", "DDR4 2400 U-DIMM", "DDR3L-1600 VLP U-DIMM", "DDR3L 1600 U-DIMM"]
        ], 
        "gpu" => [
            "Nvidia" => ["NVIDIA GEFORCE RTX 3080", "NVIDIA GEFORCE RTX 3090", "NVIDIA GEFORCE RTX 3070", "NVIDIA GEFORCE RTX 3060 Ti", "NVIDIA GEFORCE RTX 3070 Ti"], 
            "AMD" => ["Radeon RX 6900 XT", "Radeon RX 6800 XT", "Radeon RX 6800"], 
            "EVGA" => ["EVGA GeForce RTX 3090 XC3 BLACK GAMING, 24G-P5-3971-KR, 24GB GDDR6X, iCX3 Cooling, ARGB LED", "EVGA GeForce RTX 3090 XC3 GAMING, 24G-P5-3973-KR, 24GB GDDR6X, iCX3 Cooling, ARGB LED, Metal Backplate", "EVGA GeForce RTX 3090 XC3 ULTRA GAMING, 24G-P5-3975-KR, 24GB GDDR6X, iCX3 Cooling, ARGB LED, Metal Backplate", "EVGA GeForce RTX 3070 Ti XC3 ULTRA GAMING, 08G-P5-3785-KL, 8GB GDDR6X, iCX3 Cooling, ARGB LED, Metal Backplate" ,"EVGA GeForce RTX 3080 Ti XC3 ULTRA HYBRID GAMING, 12G-P5-3958-KR, 12GB GDDR6X, ARGB LED, Metal Backplate"], 
            "ASUS" => ["ROG Strix GeForce RTX 3050 OC Edition 8GB", "DUAL-RTX3050-O8G", "TUF-RTX3080-O10G-V2-GAMING", "TUF-RTX3060-O12G-V2-GAMING" ,"ROG-STRIX-RTX3080-O10G-WHITE-V2"], 
            "Gigabyte" => ["AORUS Radeon RX 6900 XT MASTER 16G (rev. 2.0)", "AORUS GeForce RTX 3080 XTREME WATERFORCE 10G", "AORUS GeForce RTX 3080 Ti XTREME 12G", "Radeon RX 6800 XT GAMING OC 16G" ,"AORUS GeForce RTX 3080 XTREME WATERFORCE WB 10G"], 
            "MSI" => ["GeForce RTX 3090 SUPRIM X 24G", "GeForce RTX 3080 GAMING Z TRIO 12G LHR", "GeForce RTX 3080 Ti GAMING X TRIO 12G", "GeForce RTX 3080 GAMING Z TRIO 10G LHR" ,"GeForce RTX 3080 SUPRIM X 12G LHR"]
        ], 
        "psu" => [
            "Corsair" => ["CX Series CX750F RGB — 750 Watt 80 Plus Bronze Certified Fully Modular RGB PSU"], 
            "BeQuiet" => ["DARK POWER PRO 12"], 
            "CoolerMaster" => ["XG750 Plus Platinum", "XG850 Plus Platinum"], 
            "EVGA" => ["EVGA SuperNOVA 650 P5, 80 Plus Platinum 650W"], 
            "ThermalLake" => ["Toughpower GF1 850W Snow - TT Premium Edition", "Toughpower GF1 750W Snow - TT Premium Edition"]
        ], 
        "storage" => [
            "Samsung" => ["980 PRO w Heatsink PCIe® 4.0 NVMe™ SSD 2TB", "980 PCIe® 3.0 NVMe® SSD 250GB"],
            "WesternDigital" => ["WD_BLACK™ SN850 NVMe™ SSD", "WD_BLACK SN750 SE NVMe™ SSD"], 
            "Seagate" => ["FireCuda 510 SSD, FireCuda SSHD"], 
            "Intel" => ["Intel® Optane™ SSD 905P Series (1.5TB,  Height PCIe x4, 20nm, 3D XPoint™)"], 
            "Toshiba" => ["Toshiba OCZ RD400 PCIe 512GB(RVD400-M22280-512G)"], 
            "Kingston" => ["Kingston 240GB A400 SATA 3 2.5 Internal SSD SA400S37 240G", "DC1000B M.2 NVMe SSD", "Kingston FURY Renegade PCIe 4.0 NVMe M.2 SSD"]
        ], 
        "cpu_cool" => [
            "Corsair" => ["Hydro Series H100x High Performance Liquid CPU Cooler", "iCUE H100i ELITE CAPELLIX Liquid CPU Cooler"],
            "NZXT" => ["Kraken Z63", "Kraken Z53 RGB"],
            "Noctua" => ["Noctua NH-U14S", "NH-D15S chromax.black", "NH-D15"],
            "BeQuiet" => ["DARK ROCK PRO 4", "DARK ROCK TF 2"],
            "CoolerMaster" => ["Cooler Master MasterAir MA624 Stealth", "MASTERAIR MA620M", "HYPER 212 EVO"],
            "EK" => ["EK-AIO Basic 240", "EK-AIO 240 D-RGB", "EK-AIO Basic 360"], 
            "Deepcool" => ["Deepcool Assassin III", "AK620", "GAMMAXX GTE V2 BLACK"]
        ], 
        "sys_cool" => [
            "Noctua" => ["Noctua NF-S12B redux-1200", "NF-A20 PWM", "NF-A14 PWM", "NF-A14 industrialPPC-2000 PWM"], 
            "NZXT" => ["Aer RGB 2 140mm", "Aer F 120mm"], 
            "CoolerMaster" => ["Cooler Master MF120R A-RGB", "Masterfan MF140 Halo White Edition"], 
            "BeQuiet" => ["SILENT WINGS 3 140mm PWM", "SILENT WINGS 3 120mm PWM high-speed"],
            "Corsair" => ["Corsair LL120 RGB"],
            "Thermaltake" => ["Thermaltake Toughfan 12 Turbo"],
            "Scythe" => ["Scythe Kaze Flex 120 PWM"]
        ], 
        "case" => [
            "NZXT" => ["H210", "H210i"], 
            "BeQuiet" => ["DARK BASE PRO 900 Orange rev. 2", "SILENT BASE 802 Window Black"], 
            "Corsair" => ["Corsair Obsidian 1000D", "Corsair iCUE 5000T RGB", "Corsair Carbide 275R", "iCUE 7000X RGB Tempered Glass Full-Tower ATX PC Case — Black", "7000D AIRFLOW Full-Tower ATX PC Case — Black"], 
            "CoolerMaster" => ["Cooler Master Silencio S600", "Cooler Master Cosmos C700P", "HAF 500", "MasterBox 500"], 
            "LianLi" => ["Lian-Li PC-011 Dynamic", "ODYSSEY X", "O11 Dynamic EVO"],
            "Phanteks" => ["Phanteks Evolv X", "Phanteks Evolv Shift X"],
            "Fractal" => ["Fractal Design Meshify-C", "Fractal Design Define R5"]
        ]
    ];
?>