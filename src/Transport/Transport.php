<?php
namespace BromineMai\CorYar\Transport;
use BromineMai\CorYar\Body\Request;
use BromineMai\CorYar\Body\Response;

Interface Transport {
    /**
     * @param string $address
     * @param array $options
     * @return mixed
     * @author Jiankang maijiankang@foxmail.com
     */
    public function open($address,$options);

    /**
     *
     * @param Request $request
     * @author Jiankang maijiankang@foxmail.com
     */
    public function send($request);

    /**
     * @param Request $request
     * @author Jiankang maijiankang@foxmail.com
     */
    public function exec($request);

    /**
     * @param Request $request
     * @return Response
     * @author Jiankang maijiankang@foxmail.com
     */
    public function close($request);

}
