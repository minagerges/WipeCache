<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Processor
$permission = 'empty_cache';
if (!$this->modx->hasPermission($permission)){
    $modx->log(modX::LOG_LEVEL_ERROR,$modx->lexicon('permission_denied'). " {$permission}" ); //sent only first time (modx issue)
    $modx->log(modX::LOG_LEVEL_INFO,'COMPLETED'); //console backport compatibility
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

$cachedir = $modx->getOption('core_path')."cache";

$modx->log(modX::LOG_LEVEL_ERROR,'WipeCache: Wiping ' . $cachedir  );
//$modx->log(modX::LOG_LEVEL_INFO,'COMPLETED'); //sending complete will stop console from listening more. ()
sleep(1);

//wipe($modx);


$instance = new Wiper();
$modx->log(modX::LOG_LEVEL_INFO,'Starting ...');
$instance->rrmdir($modx,$cachedir,$cachedir);
class Wiper {
    public $folders = 0;
    public $files = 0;
    public $skip = array('logs', 'registry','registry/mgr/wipecache' ,"lexicon_topics/lexicon/en/core", "context_settings"); //ToDo: create settings and get value from there
    
    function rrmdir($modx, $dir, $parent){
            //wipe($modx);
            $absolutedir = str_replace ($parent.'/', '', $dir);
//$modx->log(modX::LOG_LEVEL_INFO,"Handling: $dir");
            
            if (is_dir($dir)){
//sleep(1);
                $objects = scandir($dir);
                foreach ($objects as $object) 
                {
                        if ($object != "." && $object != "..") 
                        {
                            if (filetype($dir."/".$object) == "dir") //it's a folder loop it
                            {
                               //if ($dir."/".$object == ($parent."/logs"))
                               if ($dir.'/'.$object)
                                
                               //if (in_array($object, $this->skip))
//$modx->log(modX::LOG_LEVEL_WARN,"In array: {$object}");
                                       
                               $absolute = str_replace( $parent.'/','' , $dir.'/'. $object); 
//$modx->log(modX::LOG_LEVEL_INFO,">> folder: {$object} - {$absolute}");
                               if (in_array($absolute, $this->skip)) {
                                    $modx->log(modX::LOG_LEVEL_WARN,"Skipped folder: {$absolute}");
                                    //add parent folders to array
                                    /*
                                    $add = chop($absolute, '/'.$object);
                                    $add != $absolute? array_push($this->skip, $add) : '';
                                    */
                                    
                                    $ttmp = $absolute;
                                    $max = 0;                                    
                                    while (strpos($add, "/") !== true && $max < 5)
                                    {
                                        $child = "/" . substr(strrchr($absolute, "/"), 1);
                                        $add = str_replace($child, '', $absolute);//chop($ttmp, $child); removed n from lexecon/
                                        $absolute = $add;
                                        if (!in_array($add, $this->skip)){
                                            array_push($this->skip, $add);
                                            $modx->log(modX::LOG_LEVEL_WARN,"Added to Skip: {$add}");
                                        }
                                        $max++;
                                        //sleep(1);
                                    }
                                    
                                    
                               }
                               else{
//$modx->log(modX::LOG_LEVEL_WARN,"Looping: $dir/$object");
                                   $this->rrmdir($modx, $dir."/".$object, $parent);
                                    //$msgs .= $dir."/".$object."<br>";
                               }

                            }
                            else //it's a file delete it
                            {
                                unlink   ($dir."/".$object);
                                //echo $dir.$object."</br>";
                                $this->files++;
                            }
                        }
                }
                reset($objects);

                if ($dir != $parent) //folder is empty
                {   if(!in_array($absolutedir, $this->skip)) {
//                        $modx->log(modX::LOG_LEVEL_INFO,"Deleting folder: $absolutedir" );
                        rmdir($dir);
                        $this->folders++;
                    }
                    else
                        $modx->log(modX::LOG_LEVEL_WARN,"Not deleting folder: $absolutedir");
                }
                else { 
                    $modx->log(modX::LOG_LEVEL_INFO,"Deleted {$this->folders} folders.");
                    $modx->log(modX::LOG_LEVEL_INFO,"Deleted {$this->files} files.");
                    $modx->log(modX::LOG_LEVEL_WARN,'Done!');
                    $modx->log(modX::LOG_LEVEL_WARN,'Skipped' . print_r($this->skip, true));
                }

            }
            else { $modx->log(modX::LOG_LEVEL_ERROR,"Modx Cache: $cachedir is not a folder."  ); }

    }
    
}



function wipe($modx) {
            $modx->log(modX::LOG_LEVEL_INFO,'>>>>>>>>>>>An information message in normal colors.');
            sleep(1);
            $modx->log(modX::LOG_LEVEL_ERROR,'>>>>>>>>>>>An error in red!');
            sleep(1);
            $modx->log(modX::LOG_LEVEL_WARN,'>>>>>>>A warning in blue!');

            
}

sleep(1); //to avoid console skipping late messages

$modx->log(modX::LOG_LEVEL_INFO,'COMPLETED'); //console backport compatibility
//sleep(2);
return $modx->error->success(); //commented to avoid 