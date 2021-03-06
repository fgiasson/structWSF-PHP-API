<?php

  /*! @ingroup StructWSFPHPAPIWebServices structWSF PHP API Web Services */
  //@{

  /*! @file \StructuredDynamics\structwsf\php\api\ws\auth\registrar\ws\AuthRegistrarWsQuery.php
      @brief AuthRegistrarWsQuery class description
   */

  namespace StructuredDynamics\structwsf\php\api\ws\auth\registrar\ws;

  /**
  * Auth Registrar WS Query to a structWSF Auth Registrar WS web service endpoint
  * 
  * The Auth Registrar: WS Web service is used to register a Web service endpoint 
  * to the WSF (Web Services Framework). Once a Web service is registered to a WSF, 
  * it can then be used by other Web services, become accessible to users, etc.  
  * 
  * Here is a code example of how this class can be used by developers: 
  * 
  * @code
  * 
  *  use \StructuredDynamics\structwsf\framework\Namespaces;                     
  *  use \StructuredDynamics\structwsf\php\api\ws\auth\registrar\ws\AuthRegistrarWsQuery;
  *  use \StructuredDynamics\structwsf\php\api\ws\auth\lister\AuthListerQuery;
  *  use \StructuredDynamics\structwsf\php\api\framework\CRUDPermission;
  * 
  *  // Register a new web service endpoint to the structWSF instance
  *  $arws = new AuthRegistrarWsQuery("http://localhost/ws/");
  * 
  *  // Define the title 
  *  $arws->title("A new web service endpoint");
  *    
  *  // Define the endpoint's URI
  *  $arws->endpointUri("http://localhost/wsf/ws/new/");
  *  
  *  // Define the access URL
  *  $arws->endpointUrl("http://localhost/ws/new/");
  *  
  *  // Specifies that READ permission are needed to use this web service endpoint
  *  $arws->crudUsage(new CRUDPermission(FALSE, TRUE, FALSE, FALSE));
  *  
  *  $arws->send();
  * 
  *  if($arws->isSuccessful())
  *  {
  *    // Now, let's use the auth: lister endpoint to make sure we can see it in the structWSF instance
  *    $authlister = new AuthListerQuery("http://localhost/ws/");
  *    
  *    // Specifies that we want to get all the list of all registered web service endpoints.
  *    $authlister->getRegisteredWebServiceEndpointsUri();
  *    
  *    // Send the auth lister query to the endpoint
  *    $authlister->send();
  *    
  *    // Get back the resultset returned by the endpoint
  *    $resultset = $authlister->getResultset()->getResultset();
  *    
  *    $webservices = array();
  * 
  *    // Get all the URIs from the resultset array
  *    foreach($resultset["unspecified"] as $list)
  *    {
  *      foreach($list[Namespaces::$rdf."li"] as $item)
  *      {
  *        array_push($webservices, $item["uri"]);
  *      }
  *    }    
  *    
  *    print_r($webservices);
  *  }
  *  else
  *  {
  *    echo "Web service registration failed: ".$arws->getStatus()." (".$arws->getStatusMessage().")\n";
  *    echo $arws->getStatusMessageDescription();  
  *  }  
  * 
  * @endcode
  * 
  * @see http://techwiki.openstructs.org/index.php/Auth_Registrar:_WS
  * 
  * @author Frederick Giasson, Structured Dynamics LLC.  
  */
  class AuthRegistrarWsQuery extends \StructuredDynamics\structwsf\php\api\framework\WebServiceQuery
  {
    /**
    * Constructor
    * 
    * @param mixed $network structWSF network where to send this query. Ex: http://localhost/ws/
    */
    function __construct($network)
    {
      // Set the structWSF network to use for this query.
      $this->setNetwork($network);
      
      // Set default configarations for this web service query
      $this->setSupportedMimes(array("text/xml", 
                                     "application/json", 
                                     "application/rdf+xml",
                                     "application/rdf+n3",
                                     "application/iron+json",
                                     "application/iron+csv"));
                                    
      $this->setMethodGet();

      $this->mime("resultset");
      
      $this->setEndpoint("auth/registrar/ws/"); 
      
      // Set default parameters for this query
      $this->sourceInterface("default");      
    }
    
    /**
    * Title of the web service to register to this structWSF instance
    * 
    * **Required**: This function must be called before sending the query 
    * 
    * @param mixed $title Title of the web service to register
    * 
    * @see http://techwiki.openstructs.org/index.php/Auth_Registrar:_WS#Web_Service_Endpoint_Information
    * 
    * @author Frederick Giasson, Structured Dynamics LLC. 
    */
    public function title($title)
    {
      $this->params["title"] = urlencode($title);
    }  
       
    /**
    * URL of the web service endpoint where to send the HTTP queries
    * 
    * **Required**: This function must be called before sending the query 
    * 
    * @param mixed $url URL of the web service endpoint that people will access on the web
    * 
    * @see http://techwiki.openstructs.org/index.php/Auth_Registrar:_WS#Web_Service_Endpoint_Information
    * 
    * @author Frederick Giasson, Structured Dynamics LLC. 
    */
    public function endpointUrl($url)
    {
      $this->params["endpoint"] = urlencode($url);
    }
        
    /**
    * URI of the web service to register to the structWSF instance. The URI is the unique
    * idenfier of the endpoint, in the structWSF instance. That URI **is not** the URL
    * access endpoint. However, both can be the same. 
    * 
    * *Note:* in structWSF, when another web service require the URI/Identifier of a web
    *         service, it is really the URI of that web service, and not its access URL.
    * 
    * **Required**: This function must be called before sending the query 
    * 
    * @param mixed $uri URI of the web service to register to the structWSF instance
    * 
    * @see http://techwiki.openstructs.org/index.php/Auth_Registrar:_WS#Web_Service_Endpoint_Information
    * 
    * @author Frederick Giasson, Structured Dynamics LLC.
    */
    public function endpointUri($uri)
    {
      $this->params["ws_uri"] = urlencode($uri);
    }
    
    /**
    * Define the CRUD usage of the endpoint. The CRUD usage are the 4 CRUD operations that the
    * endpoint can perform on the data hosted on the structWSF instance. For example, the
    * CRUD: Create endpoint has the CRUD usage: <True,False,False,False>. This means that it
    * perform creation operations with the data. This means that the user that will send a request
    * to the CRUD: Create web service endpoint will need to have Create permissions on the target
    * dataset in order to be able to use that web service endpoint on that target dataset.
    * 
    * @param mixed $crudPermission
    * 
    * @param mixed $crudPermission A CRUDPermission object instance that define the permissions granted 
    *                              for the target IP, target Dataset and target Web Service Endpoints of 
    *                              this access permission record.
    * 
    * @see http://techwiki.openstructs.org/index.php/Auth_Registrar:_WS#Web_Service_Endpoint_Information
    * 
    * @author Frederick Giasson, Structured Dynamics LLC.
    */
    public function crudUsage($crudPermission)
    {
      $this->params["crud_usage"] = urlencode(($crudPermission->getCreate() ? "True" : "False").";".
                                        ($crudPermission->getRead() ? "True" : "False").";".
                                        ($crudPermission->getUpdate() ? "True" : "False").";".
                                        ($crudPermission->getDelete() ? "True" : "False"));       
    }          
   }       
 
//@}    
?>