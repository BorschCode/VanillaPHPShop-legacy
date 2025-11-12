<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 10.12.2017
 * Time: 22:14
 */

class breadCrumbs
{
    public static function run()
    {
	$path = $_SERVER["PHP_SELF"];
	$parts = explode('/',$path);
	if (count($parts) < 2)
    {
        //echo("home");
        //return "Home страница";
        echo "Home страница";
    }
    else
    {
        echo ("<a href=\"/\">Home</a> &raquo; ");
        for ($i = 1; $i < count($parts); $i++)
        {
            if (!strstr($parts[$i],"."))
            {
                echo("<a href=\"");
                for ($j = 0; $j <= $i; $j++) {echo $parts[$j]."/";};
                echo("\">". str_replace('-', ' ', $parts[$i])."</a> » ");
            }
            else
            {
                $str = $parts[$i];
                $pos = strrpos($str,".");
                $parts[$i] = substr($str, 0, $pos);
                echo str_replace('-', ' ', $parts[$i]);
            };
        };
    };
    }

}
