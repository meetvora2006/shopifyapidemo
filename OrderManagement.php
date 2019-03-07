<?php

//use shopifytest\Shopify;
//will manage all of the orders in/out

include 'Shopify.php';

    class OrderManagement
    {
        private $Myshopify;
        private $dbConnection;

        public function __construct(Shopify $myshopify)
        {
            $this->Myshopify = $myshopify;

            $this->dbConnection = $this->Myshopify->getDbconnection();


        }

        public function getShopifyOrder(array $id = [], $Sinceid, $limit=250)
        {
            $idList = implode(",", $id);

            $queryData = $this->Myshopify->getData('/orders.json', ["ids" => "{$idList}", "since_id" => "{$Sinceid}", "limit" => "{$limit}"]);

            $finalData = $queryData['orders'];
            $TotalCount = count($queryData['orders']);

            if ($TotalCount == 250)
            {
               $lastid = $finalData[249]['id'];

                for($x=0; $x<$lastid; $x++){

                    $queryData = $this->Myshopify->getData('/orders.json', ["ids" => "{$idList}", "since_id" => "{$lastid}", "limit" => "{$limit}"]);

                    $finalData = array_merge($finalData, $queryData['orders']);

                    if(count($queryData['orders']) == 250){

                        $x = $lastid;
                        $lastid = end($queryData['orders'])['id'];
                    }
                    else{
                        break;
                     }

                }

            }
            return $finalData;
        }


        public function insertData($ArrjsonData)
        {

          foreach ($ArrjsonData as $mYarrJsonData) {

              $jsonData = json_encode($mYarrJsonData);

              $myJsonData = mysqli_real_escape_string($this->dbConnection, $jsonData);

              $sql = "INSERT INTO ordersrecord (detail) VALUES ('$myJsonData')";


              if ($this->dbConnection->query($sql) === TRUE) {
                  echo "New record created successfully";
              } else {
                  echo "Error: " . $sql . "<br>" . $this->dbConnection->error;
              }

          }
        }



    }

$myshopify = new Shopify();

$checkorder = new OrderManagement($myshopify);

$MyqueryData = $checkorder->getShopifyOrder([] , '5100471108');

$checkorder->insertData($MyqueryData);
