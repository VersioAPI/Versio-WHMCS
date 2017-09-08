# Versio-WHMCS

1. Upload the files in the WHMCS root directory
2. Log in to WHMCS as Admin
3. Go to Setup -> Products/Services -> Domain Registrars
4. Activate the Versio module by clicking ‘Activate’
5. Click ‘Configure’
6. Enter the details that are required
-- Turn TestMode off to use production mode.

7. Save the settings.
8. The Versio WHMCS module is now ready for use.

Check if the WHMCS domain cron is added.
For details see: https://docs.whmcs.com/Crons#Domain_Sync_Cron

If you find anything that’s not working as it should, please contact our helpdesk.

# WHMCS SSL module

1. Log in to WHMCS as Admin 
2. Go to Settings -> Modules -> Versio Product addon
3. Activate the Versio module by clicking ‘Activate’
4. Click ‘Configure’
5. Enter the details that are required
-- Set TestMode off to use the production mode.
-- Permissions select Full Administrator.

6. Save the settings.
7. Go to Modules -> Versio Product addon
8. Choose ‘Synchronize SSL products with Versio’ to load all Versio SSL products
9. Add a product. Go to Settings -> Products -> Product
10. Add a category. Example: SSL Certificates
11. Choose: Add a new product
-- Product type: product
-- Category: SSL Certificates
-- Name: the SSL certificate productname you want to sell.
-- Press add.

12. In the Tab choose for external connections
-- Select module: Versio Product addons
-- Select the SSL product.

13. Set other options like price etc. in the other tabs and save.
  ( follow step 12/14 for all certificates )

If you find anything that’s not working as it should, please contact our helpdesk.