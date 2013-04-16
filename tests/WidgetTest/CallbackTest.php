<?php

namespace WidgetTest;

use Widget\Callback;

/**
 * @property \Widget\Callback $callback The WeChat callback widget
 */
class CallbackTest extends TestCase
{
    /**
     * @dataProvider providerForInputAndOutput
     */
    public function testInputAndOutput($query, $input, $data)
    {
        $cb = $this->callback;
        
        // Inject HTTP query
        parse_str($query, $gets);
        $this->request->setOption('gets', $gets);
        
        // Inject user input message
        $cb->setOption('postData', $input);
        
        $cb->fallback(function($callback){
            return "Your input is " . $callback->getIntput() . "\n"
                . "Type a number to see more \n"
                . "[0]Show menu message"
                . "[1]Show text message\n"
                . "[2]Show music message\n"
                . "[3]Show richtext message\n"
                . "[4]Show a random number\n";
        });

        $cb->is('0', function(){
            
        });
        
        $cb->is('1', function(){
            return 'text message';
        });
        
        $cb->is('2', function(Callback $cb){
            return $cb->sendMusic('Burning', 'A song of Maria Arredondo', 'url', 'HQ url', true);
        });
        
        $cb->is('3', function(Callback $cb){
            return $cb->sendArticle(array(
                'title' => 'It\'s fine today',
                'description' => 'A new day is coming~~',
                'picUrl' => 'http://pic-url',
                'url' => 'http://link-url'
            ));
        });
        
        $cb->has('ipad', function(Callback $cb){
            return $cb->sendText('Find a ipad ? ok, i will remember u', true);
        });
        
        $cb->match('/twin/i', function(Callback $cb){
            return $cb->sendText('I\'m here');
        });
        
        ob_start();
        $cb();
        $content = ob_get_clean();

        foreach ($data as $name => $value) {
            $this->assertEquals($value, $cb->{'get' . $name}());
        }
        
        $output = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);

        $this->assertEquals('toUser', $output->FromUserName);
        $this->assertEquals('fromUser', $output->ToUserName);
    }
    
    public function providerForInputAndOutput()
    {
        return array(
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('0'),
                array(
                    'input' => '0',
                    'from' => 'fromUser',
                    'to' => 'toUser',
                    'msgType' => 'text',
                    'msgId' => '1234567890123456'
                )
            ),
            array(
                'signature=c61b3d7eab5dfea9b72af0b1574ff2f4d2109583&timestamp=1366032735&nonce=1365872231',
                $this->inputTextMessage('1'),
                array(
                    'input' => '1',
                    'from' => 'fromUser',
                    'to' => 'toUser',
                    'msgType' => 'text',
                    'msgId' => '1234567890123456'
                )
            )
        );
    }
    
    public function inputTextMessage($input)
    {
        return '<xml>
                <ToUserName><![CDATA[toUser]]></ToUserName>
                <FromUserName><![CDATA[fromUser]]></FromUserName> 
                <CreateTime>1348831860</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[' . $input . ']]></Content>
                <MsgId>1234567890123456</MsgId>
                </xml>';
    }
}