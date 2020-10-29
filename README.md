# Wordpress Plugin Verifier
This plugin is designed to verify that the composer wordpress plugins are there at the end of the composer installation. 
WordpressInstaller uses composer/installers to set paths where various plugins should be installed.  Under some cases
those plugins might not be installed, with bad consequences.  This plugin checks every installed plugin, and verifies that
they have been installed in the correct folders.

Right now this plugin only checks wordpress plugins, not MU Plugins or themes.
