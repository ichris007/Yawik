<?php
/**
 * YAWIK
 *
 * @filesource
 * @copyright (c) 2013-2104 Cross Solution (http://cross-solution.de)
 * @license   AGPLv3
 */

/** Auth forms */ 
namespace Auth\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for a SocialProfilesFieldset
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 */
class SocialProfilesFieldsetFactory implements FactoryInterface
{
    
    /**
     * Creates a {@link SocialProfilesFieldset}
     * 
     * Uses config from the config key [form_element_config][attach_social_profiles_fieldset]
     * to configure fetch_url, preview_url and name or uses the defaults:
     *  - fetch_url: Route named "auth-social-profiles" with the suffix "?network=%s"
     *  - preview_url: Route named "lang/applications/detail" with the suffix "?action=social-profile&network=%s"
     *  - name: "social_profiles"
     *  
     * @param ServiceLocatorInterface $serviceLocator
     * @return SocialProfilesFieldset
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();
        $router = $services->get('Router');
        $config = $services->get('Config');
        $options = isset($config['form_element_config']['attach_social_profiles_fieldset'])
                ? $config['form_element_config']['attach_social_profiles_fieldset']
                : array();
        
        if (!isset($options['fetch_url'])) {
            $options['fetch_url'] = 
                $router->assemble(array('action' => 'fetch'), array('name' => 'auth-social-profiles'))
                . '?network=%s';
        }
        if (!isset($options['preview_url'])) {
            $options['preview_url'] = 
                $router->assemble(array('id' => 'null'), array('name' => 'lang/applications/detail'), true)
                . '?action=social-profile&network=%s';
        }
        if (isset($options['name'])) {
            $name = $options['name'];
            unset($options['name']);
        } else {
            $name = 'social_profiles';
        }
        
        $fieldset = new SocialProfilesFieldset($name, $options);
        
        return $fieldset;
        
    }
}
