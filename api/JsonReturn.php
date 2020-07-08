<?php


class JsonReturn
{
    private string $status;
    private string $info;

    private int $httpCode;

    private ?object $object;

    const ERROR = 0;
    const SUCCESS = 1;

    public function __construct(int $status, string $info, int $httpCode,object $object = null){
        $this->status = $status == 0 ? "error":"success";
        $this->httpCode = $httpCode;
        $this->info = $info;
        $this->object = $object;

        $this->show();
    }

    private function show():void{
        http_response_code($this->httpCode);
        $obj = json_encode($this->object);
        $obj = json_decode($obj,true);
        if ($this->object != null){
            $returnArray = [
                "status" => $this->status,
                "info" => $this->info,
                get_class($this->object) => $obj
            ];
        }else{
            $returnArray = [
                "status" => $this->status,
                "info" => $this->info
            ];
        }
        echo json_encode($returnArray);
    }

}