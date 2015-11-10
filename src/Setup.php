<?php //-->
/**
 * This file is part of the Eden PHP Library.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */
 
namespace Eve\Plugin\Htpasswd;

use Eden\Registry\Index as Registry;

/**
 * Htpasswd Plugin
 *
 * @package  Eve
 * @category Plugin
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Setup extends Base
{
    /**
     * @const string HTTP_AUTH Auth header
     */
    const HTTP_AUTH = 'WWW-Authenticate: Digest realm="%s",qop="auth",nonce="%s",opaque="%s"';

    /**
     * @const string UNAUTHORIZED Error header
     */
    const UNAUTHORIZED = 'HTTP/1.1 401 Unauthorized';

    /**
     * @const string ERROR_TYPE Error Type
     */
    const ERROR_TYPE = 'HTPASSWD';

    /**
     * @var string|null $realm Auth title
     */
    protected $realm = null;
    
    /**
     * Main plugin method
     *
     * @param array  $users a list of user/pass
     * @param string $realm the title of the auth popup
     *
     * @return function
     */
    public function import(array $users = array(), $realm = 'Restricted area')
    {
        //realm must be a string
        Argument::i()->test(2, 'string');
        
        $this->realm = $realm;
        $self = $this;
        
        return function (Registry $request, Registry $response) use ($users, $self) {
            //get digest
            $digest = $request->get('server', 'PHP_AUTH_DIGEST');
            
            //if no digest
            if (empty($digest)) {
                //this throws anyways
                return $self->dialog();
            }
            
            // analyze the PHP_AUTH_DIGEST variable
            $data = $self->digest($digest);
            
            //if no username
            if (!isset($users[$data['username']])) {
                //this throws anyways
                return $self->dialog();
            }
            
            // generate the valid response
            $signature = $self->getSignature($users, $data);
            
            //if it doesnt match
            if ($data['response'] !== $signature) {
                //this throws anyways
                return $self->dialog();
            }
        };
    }
    
    /**
     * Opens the browsers auth dialig
     *
     * @return void
     */
    public function dialog()
    {
        //if session has not started
        if (!session_id()) {
            //start session
            session_start();
        }
        
        //if they never tried
        if (!isset($_SESSION[self::UNAUTHORIZED])) {
            //first try
            $_SESSION[self::UNAUTHORIZED] = 1;
        } else {
            //more than one try
            $_SESSION[self::UNAUTHORIZED]++;
        }
        
        //if it's their first few tries
        if ($_SESSION[self::UNAUTHORIZED] < 3) {
            header(sprintf(self::HTTP_AUTH, $this->realm, uniqid(), md5($this->realm)));
            exit;
        }
        
        //let them try again
        unset($_SESSION[self::UNAUTHORIZED]);
        
        //you are unauthorized
        header(self::UNAUTHORIZED);
        
        //throw
        Exception::i()
            ->setMessage(self::UNAUTHORIZED)
            ->setType(self::ERROR_TYPE)
            ->trigger();
    }
    
    /**
     * Returns the response siggy
     *
     * @return string
     */
    public function getSignature($users, $data)
    {
        //make user siggy
        $userArray = array(
            $data['username'],
            $this->realm,
            $users[$data['username']]);
        
        $userSignature = md5(implode(':', $userArray));
        
        //make request siggy
        $requestArray = array(
            $_SERVER['REQUEST_METHOD'],
            $data['uri']);
        
        $requestSignature = md5(implode(':', $requestArray));
        
        //make response siggy
        $responseArray = array(
            $userSignature,
            $data['nonce'],
            $data['nc'],
            $data['cnonce'],
            $data['qop'],
            $requestSignature);
        
        return md5(implode(':', $responseArray));
    }
    
    /**
     * Extracts data from the siggy
     *
     * @param string* $string
     *
     * @return array
     */
    public function digest($string)
    {
        // protect against missing data
        $needed = array(
            'nonce' => 1,
            'nc' => 1,
            'cnonce' => 1,
            'qop' => 1,
            'username' => 1,
            'uri' => 1,
            'response' => 1);
        
        $data = array();
        $keys = implode('|', array_keys($needed));
    
        preg_match_all(
            '@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@',
            $string,
            $matches,
            PREG_SET_ORDER
        );
    
        foreach ($matches as $match) {
            $data[$match[1]] = $match[3] ? $match[3] : $match[4];
            unset($needed[$match[1]]);
        }
    
        return $needed ? false : $data;
    }
}
