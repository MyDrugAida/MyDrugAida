<?php
namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class FirebaseRealtimeDatabaseService
{
    protected $database;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(__DIR__.'/firebase_credentials.json')
                                 ->withDatabaseUri("https://mydrugaida-1234-default-rtdb.firebaseio.com/mydrugaida");
        $this->database = $factory->createDatabase();
    }

    public function storeData($uri, $data)
    {
        $reference = $this->database->getReference($uri);
        $reference->set($data);
    }

    public function getAnyUserData($userId)
    {
        $reference = $this->database->getReference($userId);
        return $reference->getValue();
    }
    
    public function checkReference($uri)
    {
        $reference = $this->database->getReference($uri)->getSnapshot();
        if($reference->exists()){
          echo "reference exists";
          return true;
        }else{
          echo "reference does not exists";
          return false;
        }
    }
}
?>