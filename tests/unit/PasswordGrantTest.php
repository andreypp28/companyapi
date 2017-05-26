<?php
use Laravel\Passport\Bridge\Scope as ScopeGI;
use Laravel\Lumen\Testing\DatabaseMigrations;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\Grant\PasswordGrant as PassGrant;
class PasswordGrantTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * 
     */
    public function testRespondToAccessTokenRequest() {
        //if (!$this->invokeMethod($scopes,'testIfthisUserHasTheCorrectScope', $user->getIdentifier())) {
    }
    public function testThisUserHasTheCorrectScope(){
         
        $user = factory(\WA\DataStore\User\User::class)->create();
        $userId = $user->id;
        $role = factory(\WA\DataStore\Role\Role::class)->create();
        
        $permission1 = factory(\WA\DataStore\Permission\Permission::class)->create();
        $permission2 = factory(\WA\DataStore\Permission\Permission::class)->create();
        $scope = factory(\WA\DataStore\Scope\Scope::class)->create(['name' => 'get']);
        $scope2 = factory(\WA\DataStore\Scope\Scope::class)->create(['name' => 'delete']);
        $user->roles()->sync([$role->id]);
        $role->perms()->sync([$permission1->id,$permission2->id]);
        $scope->permissions()->sync([$permission1->id,$permission2->id]);
        
        $scope = new ScopeGI('get');
        $scope2 = new ScopeGI('delete');
        $passG = $this->getMockBuilder('WA\Auth\PasswordGrant')
        ->setMethods(array('__construct'))
        ->disableOriginalConstructor()
        ->getMock();
        
        $res1 = $this->invokeMethod($passG,'thisUserHasTheCorrectScope',[
          [$scope], 
          $userId
          ]); 
        $this->assertTrue($res1);
       $res2 = $this->invokeMethod($passG,'thisUserHasTheCorrectScope',[
          [$scope2], 
          $userId
          ]); 
        
        $this->assertFalse($res2);
      }
      /* Call protected/private method of a class.
   *
   * @param object &$object    Instantiated object that we will run method on.
   * @param string $methodName Method name to call
   * @param array  $parameters Array of parameters to pass into method.
   *
   * @return mixed Method return.
   */
  public function invokeMethod(&$object, $methodName, array $parameters = array())
  {
      $reflection = new \ReflectionClass(get_class($object));
      $method = $reflection->getMethod($methodName);
      $method->setAccessible(true);
      return $method->invokeArgs($object, $parameters);
  }
}