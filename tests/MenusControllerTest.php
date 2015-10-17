<?php

use TypiCMS\Modules\Menus\Models\Menu;

class MenusControllerTest extends TestCase
{
    public function testAdminIndex()
    {
        // Menu::shouldReceive('all')->once()->andReturn(true);
        $view = 'menus::admin.index';

        $this->get('admin/menus');
        $this->assertEquals(200, $response->getStatusCode());
        $menus = $this->nestedViewsData[$view]['models'];

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $menus);
    }

    public function testStoreFails()
    {
        $input = ['name' => ''];
        $this->call('POST', 'admin/menus', $input);
        $this->assertRedirectedToRoute('admin.menus.create');
        $this->assertSessionHasErrors();
    }

    public function testStoreSuccess()
    {
        $object = new Menu();
        $object->id = 1;
        Menu::shouldReceive('create')->once()->andReturn($object);
        $input = ['name' => 'test'];
        $this->call('POST', 'admin/menus', $input);
        $this->assertRedirectedToRoute('admin.menus.edit', ['id' => 1]);
    }

    public function testStoreSuccessWithRedirectToList()
    {
        $object = new Menu();
        $object->id = 1;
        Menu::shouldReceive('create')->once()->andReturn($object);
        $input = ['name' => 'test', 'exit' => true];
        $this->call('POST', 'admin/menus', $input);
        $this->assertRedirectedToRoute('admin.menus.index');
    }
}
