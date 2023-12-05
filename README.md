# WooCommerce to Allegro Feed Converter

## Introduction
We are utilizing WooCommerce API to fetch product data and then parsing this information to generate a simple CSV file. 
The CSV file is structured to serve as a feed for Allegro. 
The code in this repository is developed as a POC for creating Allegro feeds from WooCommerce data.

## Purpose
The primary objective is to demonstrate the feasibility of converting WooCommerce product data into a format suitable for Allegro. This is currently implemented as a POC and is specifically tailored for a particular use case.

## Features
- Fetching product data from the WooCommerce API.
- Parsing the retrieved data into a CSV format.
- Generating a CSV file that aligns with the requirements of an Allegro feed.

## Library Used
We employ the `automattic/woocommerce` library as the client to interact with the WooCommerce API.

## Usage
To use this project, follow these steps:

1. **Enable the WooCommerce API**:
    - Log in to your WooCommerce dashboard.
    - Navigate to WooCommerce settings and enable the REST API.

2. **Configure `index.php`**:
    - Open the `index.php` file in your project.
    - Insert the following details:
      ```php
      $wooKey = 'your_woocommerce_api_key';
      $wooSecret = 'your_woocommerce_api_secret';
      $storeURL = "your_store_url";
      ```
    - Replace `'your_woocommerce_api_key'`, `'your_woocommerce_api_secret'`, and `"your_store_url"` with your actual WooCommerce API credentials and store URL.

3. **Run the Script**:
    - Execute the script via the console:
      ```
      php index.php
      ```
    - This will run the `index.php` file, which fetches the product data from WooCommerce and generates the CSV file for Allegro feed.

## Optimization and Future Work
While this project serves as a POC and is effective in its specific context, it requires further optimization for real-world application.

## Enjoy Your Work!

Remember to enjoy your work and stay positive. And in the spirit of Sheldon Cooper:

"Why did the programmer quit his job? Because he didn't get arrays (a raise)! Bazinga!"

Stay happy and keep coding!