<?php

namespace spec\Dcms\Bundle\CoreBundle\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefaultDcmsConfigSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dcms\Bundle\CoreBundle\Config\DcmsConfig');
    }

    function it_provides_a_method_to_retrieve_the_hosts_path()
    {
        $this->getHostsPath()->shouldReturn('/dcms/hosts');;
    }

    function it_provides_a_method_to_retrieve_the_default_host()
    {
        $this->getDefaultHost()->shouldReturn('default.dom');
    }

    function it_provides_a_method_to_retrieve_the_route_folder_name()
    {
        $this->getEndpointFolderName()->shouldReturn('routes');
    }
}
