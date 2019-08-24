<?php namespace CodeIgniter\Database\Live;

use CodeIgniter\Config\Config;
use CodeIgniter\I18n\Time;
use CodeIgniter\Model;
use CodeIgniter\Test\CIDatabaseTestCase;
use CodeIgniter\Test\ReflectionHelper;
use Config\Database;
use Config\Services;
use Config\Validation;
use Myth\Auth\Entities\User;
use Tests\Support\Models\EntityModel;
use Tests\Support\Models\EventModel;
use Tests\Support\Models\JobModel;
use Tests\Support\Models\SecondaryModel;
use Tests\Support\Models\SimpleEntity;
use Tests\Support\Models\UserModel;
use Tests\Support\Models\ValidErrorsModel;
use Tests\Support\Models\ValidModel;

/**
 * @group DatabaseLive
 */
class ModelTest extends CIDatabaseTestCase
{
	use ReflectionHelper;

	protected $refresh = true;

	protected $seed = 'Tests\Support\Database\Seeds\CITestSeeder';

	protected function setUp()
	{
		parent::setUp();

		$this->model = new Model($this->db);
	}

	public function tearDown()
	{
		parent::tearDown();

		Services::reset();
		Config::reset();
	}

	//--------------------------------------------------------------------

	public function testFindReturnsRow()
	{
		$model = new JobModel($this->db);

		$job = $model->find(4);

		$this->assertEquals('Musician', $job->name);
	}

	//--------------------------------------------------------------------

	public function testFindReturnsMultipleRows()
	{
		$model = new JobModel($this->db);

		$job = $model->find([1, 4]);

		$this->assertEquals('Developer', $job[0]->name);
		$this->assertEquals('Musician', $job[1]->name);
	}

	//--------------------------------------------------------------------

	public function testFindActsAsGetWithNoParams()
	{
		$model = new JobModel($this->db);

		$jobs = $model->asArray()
					  ->find();

		$this->assertCount(4, $jobs);

		$names = array_column($jobs, 'name');
		$this->assertTrue(in_array('Developer', $names));
		$this->assertTrue(in_array('Politician', $names));
		$this->assertTrue(in_array('Accountant', $names));
		$this->assertTrue(in_array('Musician', $names));
	}

	//--------------------------------------------------------------------

	public function testFindRespectsReturnArray()
	{
		$model = new JobModel($this->db);

		$job = $model->asArray()
					 ->find(4);

		$this->assertInternalType('array', $job);
	}

	//--------------------------------------------------------------------

	public function testFindRespectsReturnObject()
	{
		$model = new JobModel($this->db);

		$job = $model->asObject()
					 ->find(4);

		$this->assertInternalType('object', $job);
	}

	//--------------------------------------------------------------------

	public function testFindRespectsSoftDeletes()
	{
		$this->db->table('user')
				 ->where('id', 4)
				 ->update(['deleted' => 1]);

		$model = new UserModel($this->db);

		$user = $model->asObject()
					  ->find(4);

		$this->assertEmpty($user);

		$user = $model->withDeleted()
					  ->find(4);

		// fix for PHP7.2
		$count = is_array($user) ? count($user) : 1;
		$this->assertEquals(1, $count);
	}

	//--------------------------------------------------------------------

	public function testFindClearsBinds()
	{
		$model = new JobModel($this->db);

		$model->find(1);
		$model->find(1);

		// Binds should be reset to 0 after each one
		$binds = $model->builder()
					   ->getBinds();
		$this->assertCount(0, $binds);

		$query = $model->getLastQuery();
		$this->assertCount(1, $this->getPrivateProperty($query, 'binds'));
	}

	public function testFindAllReturnsAllRecords()
	{
		$model = new UserModel($this->db);

		$users = $model->findAll();

		$this->assertCount(4, $users);
	}

	//--------------------------------------------------------------------

	public function testFindAllRespectsLimits()
	{
		$model = new UserModel($this->db);

		$users = $model->findAll(2);

		$this->assertCount(2, $users);
		$this->assertEquals('Derek Jones', $users[0]->name);
	}

	//--------------------------------------------------------------------

	public function testFindAllRespectsLimitsAndOffset()
	{
		$model = new UserModel($this->db);

		$users = $model->findAll(2, 2);

		$this->assertCount(2, $users);
		$this->assertEquals('Richard A Causey', $users[0]->name);
	}

	//--------------------------------------------------------------------

	public function testFindAllRespectsSoftDeletes()
	{
		$this->db->table('user')
				 ->where('id', 4)
				 ->update(['deleted' => 1]);

		$model = new UserModel($this->db);

		$user = $model->findAll();

		$this->assertCount(3, $user);

		$user = $model->withDeleted()
					  ->findAll();

		$this->assertCount(4, $user);
	}

	//--------------------------------------------------------------------

	public function testFirst()
	{
		$model = new UserModel();

		$user = $model->where('id >', 2)
					  ->first();

		// fix for PHP7.2
		$count = is_array($user) ? count($user) : 1;
		$this->assertEquals(1, $count);
		$this->assertEquals(3, $user->id);
	}

	//--------------------------------------------------------------------

	public function testFirstRespectsSoftDeletes()
	{
		$this->db->table('user')
				 ->where('id', 1)
				 ->update(['deleted' => 1]);

		$model = new UserModel();

		$user = $model->first();

		// fix for PHP7.2
		$count = is_array($user) ? count($user) : 1;
		$this->assertEquals(1, $count);
		$this->assertEquals(2, $user->id);

		$user = $model->withDeleted()
					  ->first();

		$this->assertEquals(1, $user->id);
	}

	public function testFirstWithNoPrimaryKey()
	{
		$model = new SecondaryModel();

		$this->db->table('secondary')
				 ->insert([
					 'id'    => 1,
					 'key'   => 'foo',
					 'value' => 'bar',
				 ]);
		$this->db->table('secondary')
				 ->insert([
					 'id'    => 2,
					 'key'   => 'bar',
					 'value' => 'baz',
				 ]);

		$record = $model->first();

		$this->assertInstanceOf('stdClass', $record);
		$this->assertEquals('foo', $record->key);
	}

	//--------------------------------------------------------------------

	public function testSaveNewRecordObject()
	{
		$model = new JobModel();

		$data              = new \stdClass();
		$data->name        = 'Magician';
		$data->description = 'Makes peoples things dissappear.';

		$model->protect(false)
			  ->save($data);

		$this->seeInDatabase('job', ['name' => 'Magician']);
	}

	//--------------------------------------------------------------------

	public function testSaveNewRecordArray()
	{
		$model = new JobModel();

		$data = [
			'name'        => 'Apprentice',
			'description' => 'That thing you do.',
		];

		$result = $model->protect(false)
						->save($data);

		$this->seeInDatabase('job', ['name' => 'Apprentice']);
	}

	//--------------------------------------------------------------------

	public function testSaveUpdateRecordObject()
	{
		$model = new JobModel();

		$data = [
			'id'          => 1,
			'name'        => 'Apprentice',
			'description' => 'That thing you do.',
		];

		$result = $model->protect(false)
						->save($data);

		$this->seeInDatabase('job', ['name' => 'Apprentice']);
		$this->assertTrue($result);
	}

	//--------------------------------------------------------------------

	public function testSaveUpdateRecordArray()
	{
		$model = new JobModel();

		$data              = new \stdClass();
		$data->id          = 1;
		$data->name        = 'Engineer';
		$data->description = 'A fancier term for Developer.';

		$result = $model->protect(false)
						->save($data);

		$this->seeInDatabase('job', ['name' => 'Engineer']);
		$this->assertTrue($result);
	}

	//--------------------------------------------------------------------

	public function testSaveProtected()
	{
		$model = new JobModel();

		$data               = new \stdClass();
		$data->id           = 1;
		$data->name         = 'Engineer';
		$data->description  = 'A fancier term for Developer.';
		$data->random_thing = 'Something wicked'; // If not protected, this would kill the script.

		$result = $model->protect(true)
						->save($data);

		$this->assertTrue($result);
	}

	//--------------------------------------------------------------------

	public function testDeleteBasics()
	{
		$model = new JobModel();

		$this->seeInDatabase('job', ['name' => 'Developer']);

		$model->delete(1);

		$this->dontSeeInDatabase('job', ['name' => 'Developer']);
	}

	//--------------------------------------------------------------------

	public function testDeleteWithSoftDeletes()
	{
		$model = new UserModel();

		$this->seeInDatabase('user', ['name' => 'Derek Jones', 'deleted' => 0]);

		$model->delete(1);

		$this->seeInDatabase('user', ['name' => 'Derek Jones', 'deleted' => 1]);
	}

	//--------------------------------------------------------------------

	public function testDeleteWithSoftDeletesPurge()
	{
		$model = new UserModel();

		$this->seeInDatabase('user', ['name' => 'Derek Jones', 'deleted' => 0]);

		$model->delete(1, true);

		$this->dontSeeInDatabase('user', ['name' => 'Derek Jones']);
	}

	//--------------------------------------------------------------------

	public function testDeleteMultiple()
	{
		$model = new JobModel();

		$this->seeInDatabase('job', ['name' => 'Developer']);
		$this->seeInDatabase('job', ['name' => 'Politician']);

		$model->delete([1, 2]);

		$this->dontSeeInDatabase('job', ['name' => 'Developer']);
		$this->dontSeeInDatabase('job', ['name' => 'Politician']);
		$this->seeInDatabase('job', ['name' => 'Accountant']);
	}

	//--------------------------------------------------------------------

	public function testDeleteNoParams()
	{
		$model = new JobModel();

		$this->seeInDatabase('job', ['name' => 'Developer']);

		$model->where('id', 1)
			  ->delete();

		$this->dontSeeInDatabase('job', ['name' => 'Developer']);
	}

	//--------------------------------------------------------------------

	public function testPurgeDeleted()
	{
		$model = new UserModel();

		$this->db->table('user')
				 ->where('id', 1)
				 ->update(['deleted' => 1]);

		$model->purgeDeleted();

		$users = $model->withDeleted()
					   ->findAll();

		$this->assertCount(3, $users);
	}

	//--------------------------------------------------------------------

	public function testOnlyDeleted()
	{
		$model = new UserModel($this->db);

		$this->db->table('user')
				 ->where('id', 1)
				 ->update(['deleted' => 1]);

		$users = $model->onlyDeleted()
					   ->findAll();

		$this->assertCount(1, $users);
	}

	//--------------------------------------------------------------------

	public function testChunk()
	{
		$model = new UserModel();

		$rowCount = 0;

		$model->chunk(2, function ($row) use (&$rowCount) {
			$rowCount++;
		});

		$this->assertEquals(4, $rowCount);
	}

	//--------------------------------------------------------------------

	public function testValidationBasics()
	{
		$model = new ValidModel($this->db);

		$data = [
			'name'        => null,
			'description' => 'some great marketing stuff',
		];

		$this->assertFalse($model->insert($data));

		$errors = $model->errors();

		$this->assertEquals('You forgot to name the baby.', $errors['name']);
	}

	//--------------------------------------------------------------------

	public function testValidationWithSetValidationMessage()
	{
		$model = new ValidModel($this->db);

		$data = [
			'name'        => null,
			'description' => 'some great marketing stuff',
		];

		$model->setValidationMessage('name', [
			'required'   => 'Your baby name is missing.',
			'min_length' => 'Too short, man!',
		]);
		$this->assertFalse($model->insert($data));

		$errors = $model->errors();

		$this->assertEquals('Your baby name is missing.', $errors['name']);
	}

	//--------------------------------------------------------------------

	public function testValidationWithSetValidationMessages()
	{
		$model = new ValidModel($this->db);

		$data = [
			'name'        => null,
			'description' => 'some great marketing stuff',
		];

		$model->setValidationMessages([
			'name' => [
				'required'   => 'Your baby name is missing.',
				'min_length' => 'Too short, man!',
			],
		]);

		$this->assertFalse($model->insert($data));

		$errors = $model->errors();

		$this->assertEquals('Your baby name is missing.', $errors['name']);
	}
	//--------------------------------------------------------------------

	public function testValidationPlaceholdersSuccess()
	{
		$model = new ValidModel($this->db);

		$data = [
			'name'  => 'abc',
			'id'    => 13,
			'token' => 13,
		];

		$this->assertTrue($model->validate($data));
	}

	public function testValidationPlaceholdersFail()
	{
		$model = new ValidModel($this->db);

		$data = [
			'name'  => 'abc',
			'id'    => 13,
			'token' => 12,
		];

		$this->assertFalse($model->validate($data));
	}

	public function testSkipValidation()
	{
		$model = new ValidModel($this->db);

		$data = [
			'name'        => '2',
			'description' => 'some great marketing stuff',
		];

		$this->assertInternalType('numeric', $model->skipValidation(true)
												   ->insert($data));
	}

	public function testCleanValidationRemovesAllWhenNoDataProvided()
	{
		$model   = new Model($this->db);
		$cleaner = $this->getPrivateMethodInvoker($model, 'cleanValidationRules');

		$rules = [
			'name' => 'required',
			'foo'  => 'bar',
		];

		$rules = $cleaner($rules, null);

		$this->assertEmpty($rules);
	}

	public function testCleanValidationRemovesOnlyForFieldsNotProvided()
	{
		$model   = new Model($this->db);
		$cleaner = $this->getPrivateMethodInvoker($model, 'cleanValidationRules');

		$rules = [
			'name' => 'required',
			'foo'  => 'required',
		];

		$data = [
			'foo' => 'bar',
		];

		$rules = $cleaner($rules, $data);

		$this->assertTrue(array_key_exists('foo', $rules));
		$this->assertFalse(array_key_exists('name', $rules));
	}

	public function testCleanValidationReturnsAllWhenAllExist()
	{
		$model   = new Model($this->db);
		$cleaner = $this->getPrivateMethodInvoker($model, 'cleanValidationRules');

		$rules = [
			'name' => 'required',
			'foo'  => 'required',
		];

		$data = [
			'foo'  => 'bar',
			'name' => null,
		];

		$rules = $cleaner($rules, $data);

		$this->assertTrue(array_key_exists('foo', $rules));
		$this->assertTrue(array_key_exists('name', $rules));
	}

	public function testValidationPassesWithMissingFields()
	{
		$model = new ValidModel();

		$data = [
			'foo' => 'bar',
		];

		$result = $model->validate($data);

		$this->assertTrue($result);
	}

	public function testValidationWithGroupName()
	{
		$config            = new \Config\Validation();
		$config->grouptest = [
			'name'  => [
				'required',
				'min_length[3]',
			],
			'token' => 'in_list[{id}]',
		];

		$data = [
			'name'  => 'abc',
			'id'    => 13,
			'token' => 13,
		];

		\CodeIgniter\Config\Config::injectMock('Validation', $config);

		$model = new ValidModel($this->db);
		$this->setPrivateProperty($model, 'validationRules', 'grouptest');

		$this->assertTrue($model->validate($data));
	}

	//--------------------------------------------------------------------

	public function testCanCreateAndSaveEntityClasses()
	{
		$model = new EntityModel($this->db);

		$entity = $model->where('name', 'Developer')
						->first();

		$this->assertInstanceOf(SimpleEntity::class, $entity);
		$this->assertEquals('Developer', $entity->name);
		$this->assertEquals('Awesome job, but sometimes makes you bored', $entity->description);

		$entity->name       = 'Senior Developer';
		$entity->created_at = '2017-07-15';

		$date = $this->getPrivateProperty($entity, 'created_at');
		$this->assertInstanceOf(Time::class, $date);

		$this->assertTrue($model->save($entity));

		$this->seeInDatabase('job', ['name' => 'Senior Developer', 'created_at' => '2017-07-15 00:00:00']);
	}

	/**
	 * @see https://github.com/codeigniter4/CodeIgniter4/issues/580
	 */
	public function testPasswordsStoreCorrectly()
	{
		$model = new UserModel();

		$pass = password_hash('secret123', PASSWORD_BCRYPT);

		$data = [
			'name'    => $pass,
			'email'   => 'foo@example.com',
			'country' => 'US',
			'deleted' => 0,
		];

		$model->insert($data);

		$this->seeInDatabase('user', $data);
	}

	public function testInsertEvent()
	{
		$model = new EventModel();

		$data = [
			'name'    => 'Foo',
			'email'   => 'foo@example.com',
			'country' => 'US',
			'deleted' => 0,
		];

		$model->insert($data);

		$this->assertTrue($model->hasToken('beforeInsert'));
		$this->assertTrue($model->hasToken('afterInsert'));
	}

	public function testUpdateEvent()
	{
		$model = new EventModel();

		$data = [
			'name'    => 'Foo',
			'email'   => 'foo@example.com',
			'country' => 'US',
			'deleted' => 0,
		];

		$id = $model->insert($data);
		$model->update($id, $data);

		$this->assertTrue($model->hasToken('beforeUpdate'));
		$this->assertTrue($model->hasToken('afterUpdate'));
	}

	public function testFindEvent()
	{
		$model = new EventModel();

		$model->find(1);

		$this->assertTrue($model->hasToken('afterFind'));
	}

	public function testDeleteEvent()
	{
		$model = new EventModel();

		$model->delete(1);

		$this->assertTrue($model->hasToken('afterDelete'));
	}

	public function testSetWorksWithInsert()
	{
		$model = new EventModel();

		$this->dontSeeInDatabase('user', [
			'email' => 'foo@example.com',
		]);

		$model->set([
			'email'   => 'foo@example.com',
			'name'    => 'Foo Bar',
			'country' => 'US',
		])
			  ->insert();

		$this->seeInDatabase('user', [
			'email' => 'foo@example.com',
		]);
	}

	public function testSetWorksWithUpdate()
	{
		$model = new EventModel();

		$this->dontSeeInDatabase('user', [
			'email' => 'foo@example.com',
		]);

		$userId = $model->insert([
			'email'   => 'foo@example.com',
			'name'    => 'Foo Bar',
			'country' => 'US',
		]);

		$model->set([
			'name' => 'Fred Flintstone',
		])
			  ->update($userId);

		$this->seeInDatabase('user', [
			'id'    => $userId,
			'email' => 'foo@example.com',
			'name'  => 'Fred Flintstone',
		]);
	}

	public function testSetWorksWithUpdateNoId()
	{
		$model = new EventModel();

		$this->dontSeeInDatabase('user', [
			'email' => 'foo@example.com',
		]);

		$userId = $model->insert([
			'email'   => 'foo@example.com',
			'name'    => 'Foo Bar',
			'country' => 'US',
		]);

		$model
			->where('id', $userId)
			->set([
				'name' => 'Fred Flintstone',
			])
			->update();

		$this->seeInDatabase('user', [
			'id'    => $userId,
			'email' => 'foo@example.com',
			'name'  => 'Fred Flintstone',
		]);
	}

	public function testUpdateArray()
	{
		$model = new EventModel();

		$data = [
			'name'    => 'Foo',
			'email'   => 'foo@example.com',
			'country' => 'US',
			'deleted' => 0,
		];

		$id = $model->insert($data);
		$model->update([1, 2], ['name' => 'Foo Bar']);

		$this->seeInDatabase('user', ['id' => 1, 'name' => 'Foo Bar']);
		$this->seeInDatabase('user', ['id' => 2, 'name' => 'Foo Bar']);
	}

	public function testInsertBatchSuccess()
	{
		$job_data = [
			[
				'name'        => 'Comedian',
				'description' => 'Theres something in your teeth',
			],
			[
				'name'        => 'Cab Driver',
				'description' => 'Iam yellow',
			],
		];

		$model = new JobModel($this->db);
		$model->insertBatch($job_data);

		$this->seeInDatabase('job', ['name' => 'Comedian']);
		$this->seeInDatabase('job', ['name' => 'Cab Driver']);
	}

	public function testInsertBatchValidationFail()
	{
		$job_data = [
			[
				'name'        => 'Comedian',
				'description' => null,
			],
		];

		$model = new JobModel($this->db);

		$this->setPrivateProperty($model, 'validationRules', ['description' => 'required']);

		$this->assertFalse($model->insertBatch($job_data));

		$error = $model->errors();
		$this->assertTrue(isset($error['description']));
	}

	public function testUpdateBatchSuccess()
	{
		$data = [
			[
				'name'    => 'Derek Jones',
				'country' => 'Greece',
			],
			[
				'name'    => 'Ahmadinejad',
				'country' => 'Greece',
			],
		];

		$model = new EventModel($this->db);

		$model->updateBatch($data, 'name');

		$this->seeInDatabase('user', [
			'name'    => 'Derek Jones',
			'country' => 'Greece',
		]);
		$this->seeInDatabase('user', [
			'name'    => 'Ahmadinejad',
			'country' => 'Greece',
		]);
	}

	//--------------------------------------------------------------------

	public function testUpdateBatchValidationFail()
	{
		$data = [
			[
				'name'    => 'Derek Jones',
				'country' => null,
			],
		];

		$model = new EventModel($this->db);
		$this->setPrivateProperty($model, 'validationRules', ['country' => 'required']);

		$this->assertFalse($model->updateBatch($data, 'name'));

		$error = $model->errors();
		$this->assertTrue(isset($error['country']));
	}

	//--------------------------------------------------------------------

	public function testSelectAndEntitiesSaveOnlyChangedValues()
	{
		$this->hasInDatabase('job', [
			'name'        => 'Rocket Scientist',
			'description' => 'Plays guitar for Queen',
			'created_at'  => date('Y-m-d H:i:s'),
		]);

		$model = new EntityModel();

		$job = $model->select('id, name')
					 ->where('name', 'Rocket Scientist')
					 ->first();

		$this->assertNull($job->description);
		$this->assertEquals('Rocket Scientist', $job->name);

		$model->save($job);

		$this->seeInDatabase('job', [
			'id'          => $job->id,
			'name'        => 'Rocket Scientist',
			'description' => 'Plays guitar for Queen',
		]);
	}

	public function testUpdateNoPrimaryKey()
	{
		$model = new SecondaryModel();

		$this->db->table('secondary')
				 ->insert([
					 'id'    => 1,
					 'key'   => 'foo',
					 'value' => 'bar',
				 ]);

		$this->dontSeeInDatabase('secondary', [
			'key'   => 'bar',
			'value' => 'baz',
		]);

		$model->where('key', 'foo')
			  ->update(null, ['key' => 'bar', 'value' => 'baz']);

		$this->seeInDatabase('secondary', [
			'key'   => 'bar',
			'value' => 'baz',
		]);
	}

	/**
	 * @see https://github.com/codeigniter4/CodeIgniter4/issues/1617
	 */
	public function testCountAllResultsRespectsSoftDeletes()
	{
		$model = new UserModel();

		// testSeeder has 4 users....
		$this->assertEquals(4, $model->countAllResults());

		$model->where('name', 'Derek Jones')
			  ->delete();

		$this->assertEquals(3, $model->countAllResults());
	}

	/**
	 * @see https://github.com/codeigniter4/CodeIgniter4/issues/1584
	 */
	public function testUpdateWithValidation()
	{
		$model = new ValidModel($this->db);

		$data = [
			'description' => 'This is a first test!',
			'name'        => 'valid',
			'id'          => 42,
			'token'       => 42,
		];

		$id = $model->insert($data);

		$this->assertTrue((bool)$id);

		$data['description'] = 'This is a second test!';
		unset($data['name']);

		$result = $model->update($id, $data);
		$this->assertTrue($result);
	}

	/**
	 * @see https://github.com/codeigniter4/CodeIgniter4/issues/1717
	 */
	public function testRequiredWithValidationEmptyString()
	{
		$model = new ValidModel($this->db);

		$data = [
			'name' => '',
		];

		$this->assertFalse($model->insert($data));
	}

	/**
	 * @see https://github.com/codeigniter4/CodeIgniter4/issues/1717
	 */
	public function testRequiredWithValidationNull()
	{
		$model = new ValidModel($this->db);

		$data = [
			'name' => null,
		];

		$this->assertFalse($model->insert($data));
	}

	/**
	 * @see https://github.com/codeigniter4/CodeIgniter4/issues/1717
	 */
	public function testRequiredWithValidationTrue()
	{
		$model = new ValidModel($this->db);

		$data = [
			'name'        => 'foobar',
			'description' => 'just becaues we have to',
		];

		$this->assertTrue($model->insert($data) !== false);
	}

	/**
	 * @see https://github.com/codeigniter4/CodeIgniter4/issues/1574
	 */
	public function testValidationIncludingErrors()
	{
		$model = new ValidErrorsModel($this->db);

		$data = [
			'description' => 'This is a first test!',
			'name'        => 'valid',
			'id'          => 42,
			'token'       => 42,
		];

		$id = $model->insert($data);

		$this->assertFalse((bool)$id);

		$errors = $model->errors();

		$this->assertEquals('Minimum Length Error', $model->errors()['name']);
	}

	/**
	 * @expectedException        CodeIgniter\Exceptions\ModelException
	 * @expectedExceptionMessage `Tests\Support\Models\UserModel` model class does not specify a Primary Key.
	 */
	public function testThrowsWithNoPrimaryKey()
	{
		$model = new UserModel();
		$this->setPrivateProperty($model, 'primaryKey', '');

		$model->find(1);
	}
}
