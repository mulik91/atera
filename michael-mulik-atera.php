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


  public function __construct()
  {
    add_action('init', [$this, 'initiatePlugin']);
  }

  /**
   * Initiate the action of the plugin
   */
  public function initiatePlugin()
  {
    add_action('admin_menu', [$this, 'michael_admin_menu']);
    add_action('wp_enqueue_scripts', [$this, 'swapi_styles']);
    add_shortcode('display_swapi_table', [$this, 'echoing_swapi']);
  }

  /**
   * Adding the plugin an admin menu
   */
  public function michael_admin_menu()
  {
    add_menu_page('Star Wars Starships', 'Star Wars Starships', 'manage_options', 'star-wars-starships', [$this, 'michael_admin_menu_callback']);
  }

  /**
   * Display the admin area
   */
  public function michael_admin_menu_callback()
  {
    $file = get_plugin_files($this->admin_content_file);

    echo $file !== false ? file_get_contents(plugin_dir_path(__FILE__) . $this->admin_content_file) : 'File not found';
  }

  /**
   * Retrive wanted data
   * @param String $url dynamicly changed
   */
  public function make_api_request($url)
  {
    $args = [
      'timeout' => 10,
    ];

    $response = wp_remote_get($url, $args);

    if (is_wp_error($response)) {
      echo 'Error: ' . $response->get_error_message();
    } else {
      $body = wp_remote_retrieve_body($response);

      return json_decode($body);
    }
  }

  /**
   * fetch the whole wanted data 
   */
  public function fetch_all_data()
  {
    $all_data = [];

    while (true) {
      if ($this->api_url === null) break;

      try {
        $data = $this->make_api_request($this->api_url);

        if (isset($data->results)) {
          $this->api_url = $data->next;

          $all_data = array_merge($all_data, $data->results);
        } else {
          break;
        }
      } catch (Exception $e) {
        echo "Error fetching data from API: " . $e->getMessage();
        break;
      }
    }

    return $this->filter_wanted_data($all_data);
  }

  /**
   * filter the wanted data by the excpected keys
   */
  public function filter_wanted_data($starships)
  {

    $new_data = [];

    foreach ($starships as $starship_key => $starship) {
      foreach ($starship as $key => $value) {
        if (in_array($key, $this->wanted_keys)) {
          $new_data[$starship_key][$key] = $value;
        }
      }
    }

    return $new_data;
  }

  /**
   * generate the html content to be shown on front
   */
  public function generate_swapi_html_table()
  {
    $starships = $this->fetch_all_data();

    $table = "<table class='starshipTable'>
      <thead>
        <tr>
        <th>Name</th>
        <th>Starship Class</th>
        <th>Crew</th>
        <th>Cost in Credits</th>
        </tr>
      </thead>
      <tbody>";

    foreach ($starships as $starship) {
      $table .= "<tr>
        <td>" . $starship['name'] . "</td>
        <td>" . $starship['starship_class'] . "</td>
        <td>" . $starship['crew'] . "</td>
        <td>" . $starship['cost_in_credits'] . "</td>
      </tr>";
    }

    $table .= "</tbody>
    </table>";

    return $table;
  }


  /**
   * Create a shortcode for displaying the table anywehere user choose to.
   */

  public function echoing_swapi()
  {
    return $this->generate_swapi_html_table();
  }


  /**
   * add styling file
   */
  public function swapi_styles()
  {
    $style_path = plugins_url('css/swapi.css', __FILE__);

    wp_register_style('swapi-styles', $style_path, [], false);

    wp_enqueue_style('swapi-styles');
  }
}

$swapi = new Michael_Swapi();
