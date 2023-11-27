<?php

/*
Plugin Name: Michael Mulik Atera
Description: Assignment for Atera
Version: 1.0
Author: Michael Mulik
*/

if (!defined('ABSPATH')) {
  exit;
}

class Michael_Swapi
{
  /**
   * html content for the admin
   */
  protected $admin_content_file = '/templates/admin.html';

  /**
   * api url for the service
   */
  protected $api_url = 'https://swapi.dev/api/starships?format=json';

  /**
   * wanted keys from the api service
   */
  protected $wanted_keys = ['name', 'starship_class', 'crew', 'cost_in_credits'];
}
