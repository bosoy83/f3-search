<?php
namespace Search\Admin;

class Routes extends \Dsc\Routes\Group
{
    public function initialize()
    {
        $this->setDefaults( array(
            'namespace' => '\Search\Admin\Controllers',
            'url_prefix' => '/admin' 
        ) );
        
        $this->add( '/search', 'GET', array(
            'controller' => 'Search',
            'action' => 'index'
        ) );
        
        $this->add( '/search/page/@page', 'GET', array(
            'controller' => 'Search',
            'action' => 'index'
        ) );        
        
        //$this->addSettingsRoutes();
        //$this->addCrudGroup( 'Searches', 'Search' ); // the log of searches made on the site
    }
}