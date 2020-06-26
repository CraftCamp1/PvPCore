<?php
/**
 * Created by PhpStorm.
 * User: jkorn2324
 * Date: 2019-05-19
 * Time: 21:57
 */

declare(strict_types=1);

namespace jkorn\pvpcore\world\areas;


use jkorn\pvpcore\PvPCore;
use jkorn\pvpcore\utils\PvPCKnockback;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use stdClass;

class AreaHandler
{

    /* @var Config */
    // private $config;

    /** @var PvPCArea[] */
    private $areas;

    /** @var string */
    private $areaFile;

    /** @var Server */
    private $server;

    /** @var PvPCore */
    private $core;

    public function __construct(PvPCore $core)
    {
        $this->areas = [];

        $this->core = $core;
        $this->server = $core->getServer();

        $this->initAreas($core->getDataFolder());
    }

    /**
     * @param string $dataFolder
     *
     * Initializes the areas and stores them to the array.
     */
    private function initAreas(string $dataFolder): void
    {
        $this->areaFile = $dataFolder . "area.json";

        if(!file_exists($this->areaFile))
        {
            $file = fopen($this->areaFile, "w");
            fclose($file);

            $this->decodeAreas($dataFolder . "/config.yml", false);
            return;
        }

        $this->decodeAreas($this->areaFile);
    }

    /**
     * @param string $file
     * @param bool $json
     *
     * Decodes the areas accordingly, used for initialization only.
     */
    private function decodeAreas(string $file, bool $json = true): void
    {
        if($json)
        {
            $contents = json_decode(file_get_contents($file), true);
            foreach($contents as $areaName => $data)
            {
                $area = PvPCArea::decode($areaName, $data);
                if($area instanceof PvPCArea)
                {
                    $this->areas[$area->getName()] = $area;
                }
            }
        }
        else
        {
            if(!file_exists($file))
            {
                return;
            }

            $contents = yaml_parse_file($file)["areas"];
            foreach($contents as $name => $data)
            {
                $area = PvPCArea::decodeLegacy($name, $data);
                if($area instanceof PvPCArea)
                {
                    $this->areas[$area->getName()] = $area;
                }
            }
        }
    }

    /**
     * @param stdClass $info
     * @param Level $level
     * @param string $name
     * @return bool - Returns true if the area is successfully created.
     *
     * Create an area based on the information and its name.
     */
    public function createArea(stdClass $info, Level $level, string $name): bool
    {
        // Checks if area exists.
        if(isset($this->areas[$name]))
        {
            return false;
        }

        if(isset($info->firstPos, $info->secondPos))
        {
            /** @var Vector3 $firstPos */
            $firstPos = $info->firstPos;
            /** @var Vector3 $secondPos */
            $secondPos = $info->secondPos;

            $area = new PvPCArea(
                $name,
                $level,
                $firstPos,
                $secondPos,
                new PvPCKnockback()
            );

            $this->areas[$area->getName()] = $area;

            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @return bool - Returns true if area has been deleted.
     *
     * Deletes the area based on the name.
     */
    public function deleteArea(string $name): bool
    {
        if(isset($this->areas[$name]))
        {
            unset($this->areas[$name]);
            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @return bool
     *
     * Determines if the area exists.
     */
    public function doesAreaExist(string $name): bool
    {
        // return !is_null($this->getArea($name));
        return isset($this->areas[$name]);
    }

    /**
     * @param string $name
     * @return PvPCArea|null
     *
     * Gets a area based on the name.
     */
    public function getArea($name)
    {
        if(isset($this->areas[$name]))
        {
            return $this->areas[$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @param PvPCArea $area
     *
     * Updates a pvp area based on the name and area.
     */
    public function updateArea(string $name, PvPCArea $area): void
    {
        // TODO
        /* $areas = $this->config->get("areas");

        if(array_key_exists($name, $areas)) {
            $areas[$name] = $area->toMap();
            $this->config->set("areas", $areas);
            $this->config->save();
        } */
    }

    /**
     * @return array|PvPCArea[]
     *
     * Gets the areas.
     */
    public function getAreas()
    {
        return $this->areas;
    }

    /**
     * @return string
     *
     * Lists the areas in the manager.
     */
    public function listAreas(): string
    {
        /* $areas = $this->getAreas();

        $len = count($areas) - 1;

        $count = 0;

        $result = "List of PvPAreas: {Areas}";

        $replaced = "None";

        if($len + 1 > 0) {

            $replaced = "";

            foreach($areas as $area) {

                $comma = ($count === $len) ? "" : ", ";

                $level = $area->getLevel()->getName();

                $replaced .= $area->getName() . " ($level)" . $comma;

                $count++;
            }
        }

        return str_replace("{Areas}", $replaced, $result); */
        // TODO: list areas

        return "";
    }

    /**
     * @param Player $player
     * @return bool
     *
     * Determines if the player is in the area.
     */
    public function isInArea(Player $player): bool
    {
        // return !is_null($this->getClosestAreaTo($player));
        // TODO: if the player is in area
        return false;
    }

    /**
     * @param Player $a
     * @param Player $b
     * @return bool
     *
     * Determines if the players are in the same area.
     */
    public function isInSameAreas(Player $a, Player $b) : bool
    {

        /* $result = false;

        if($this->isInArea($a) and $this->isInArea($b)) {

            $bArea = $this->getClosestAreaTo($b);

            $aArea = $this->getClosestAreaTo($a);

            if(!is_null($bArea) and !is_null($aArea))
                $result = $bArea->equals($aArea);

        }

        return $result; */
        // TODO: if the players are in the same areas.

        return false;
    }
}