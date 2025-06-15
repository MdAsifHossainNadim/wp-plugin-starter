<?php

namespace WPPluginStarter\Tests\Core\Data\Stores;

use WPPluginStarter\Core\Data\Models\Settings_Model;
use WPPluginStarter\Core\Data\Stores\Settings_Data_Store;
use WPPluginStarter\Tests\WPPluginStarterUnitTestCase;

/**
 * Test case for the SettingsDataStore class.
 *
 * @covers \WPPluginStarter\Core\Data\Stores\Settings_Data_Store
 */
class SettingsDataStoreTest extends WPPluginStarterUnitTestCase {
	/**
	 * @var Settings_Data_Store
	 */
	private $store;

	/**
	 * Set up before each test
	 */
	public function setUp(): void {
		parent::setUp();

		// Use the mock data store as our test subject
		$this->store = $this->mock_settings_store;

		// Clear any previous test data
		$this->store->clear();

		// Make sure tables are created
		$this->store->create_tables();
	}

	/**
	 * Create test features for testing
	 */
	protected function create_test_settings() {
		// Create test setting 1
		$setting1 = new Settings_Model();
		$setting1->set_name( 'test-setting-1' );
		$setting1->set_group( 'test-group' );
		$setting1->set_description( 'Test setting description' );
		$setting1->set_values( array( 'test_key' => 'test_value' ) );
		$setting1->set_default( array( 'test_key' => 'default_value' ) );
		$this->store->create( $setting1 );

		// Create test setting 2
		$setting2 = new Settings_Model();
		$setting2->set_name( 'test-setting-2' );
		$setting2->set_group( 'test-group' );
		$setting2->set_description( 'Test setting 2 description' );
		$setting2->set_values( array( 'test_key' => 'test_value_2' ) );
		$setting2->set_default( array( 'test_key' => 'default_value_2' ) );
		$this->store->create( $setting2 );

		// Create test setting 3
		$setting3 = new Settings_Model();
		$setting3->set_name( 'test-setting-3' );
		$setting3->set_group( 'another-group' );
		$setting3->set_description( 'Test setting 3 description' );
		$setting3->set_values( array( 'test_key' => 'test_value_3' ) );
		$setting3->set_default( array( 'test_key' => 'default_value_3' ) );
		$this->store->create( $setting3 );
	}

	/**
	 * Create features for testing with mock data store
	 *
	 * @param array $overrides
	 * @param bool  $save
	 *
	 * @return Settings_Model
	 */
	protected function create_settings( array $overrides = array(), bool $save = true ): Settings_Model {
		// Default properties
		$properties = array(
			'name'        => 'test-setting-' . uniqid(),
			'group'       => 'test-group',
			'description' => 'A test setting',
			'values'      => array( 'value' => 'test' ),
			'default'     => null,
		);

		// Apply overrides
		$properties = array_merge( $properties, $overrides );

		// Create features
		$settings = new Settings_Model();

		foreach ( $properties as $key => $value ) {
			$setter = 'set_' . $key;
			if ( method_exists( $settings, $setter ) ) {
				$settings->$setter( $value );
			}
		}

		// Save to our mock data store if requested
		if ( $save ) {
			$this->store->create( $settings );
		}

		return $settings;
	}

	/**
	 * Create multiple test features
	 *
	 * @param int   $count
	 * @param array $overrides
	 * @param bool  $save
	 *
	 * @return Settings_Model[]
	 */
	protected function create_settings_multiple( int $count = 3, array $overrides = array(), bool $save = true ): array {
		$settings_array = array();

		for ( $i = 0; $i < $count; $i++ ) {
			$setting_overrides = $overrides;

			// Add index to name if not provided in overrides
			if ( ! isset( $setting_overrides['name'] ) ) {
				$setting_overrides['name'] = 'test-setting-' . ( $i + 1 );
			}

			$settings_array[] = $this->create_settings( $setting_overrides, $save );
		}

		return $settings_array;
	}

	/**
	 * Test create method creates features with correct data
	 */
	public function test_create_creates_settings_with_correct_data() {
		// Create a features object
		$settings = new Settings_Model();
		$settings->set_group( 'test-group' );
		$settings->set_name( 'test-setting' );
		$settings->set_description( 'A test setting' );
		$settings->set_values( array( 'key' => 'value' ) );
		$settings->set_default( 'default-value' );

		// Create the features
		$this->store->create( $settings );

		// Verify features was created with an ID
		$this->assertGreaterThan( 0, $settings->get_id() );

		// Verify date fields were set
		$this->assertGreaterThan( 0, $settings->get_date_created() );
		$this->assertGreaterThan( 0, $settings->get_date_modified() );

		// Verify changes were applied
		$this->assertEmpty( $settings->get_changes() );
	}

	/**
	 * Test read method loads features data by ID
	 */
	public function test_read_loads_settings_data_by_id() {
		// Create a test features
		$settings = $this->create_settings(
			array(
				'name'        => 'test-features',
				'group'       => 'test-group',
				'description' => 'Test description',
				'values'      => array( 'key' => 'value' ),
				'default'     => array( 'key' => 'default' ),
			)
		);

		$settings_id = $settings->get_id();

		// Create a new features object to read data into
		$settings_to_read = new Settings_Model();
		$settings_to_read->set_id( $settings_id );

		// Read data
		$this->store->read( $settings_to_read );

		// Verify data was properly read
		$this->assertEquals( $settings_id, $settings_to_read->get_id() );
		$this->assertEquals( 'test-features', $settings_to_read->get_name() );
		$this->assertEquals( 'test-group', $settings_to_read->get_group() );
		$this->assertEquals( 'Test description', $settings_to_read->get_description() );
		$this->assertEquals( array( 'key' => 'value' ), $settings_to_read->get_values() );
		$this->assertEquals( array( 'key' => 'default' ), $settings_to_read->get_default() );
	}

	/**
	 * Test read method loads features data by name
	 */
	public function test_read_loads_settings_data_by_name() {
		// Create a test features
		$original_settings = $this->create_settings(
			array(
				'name'        => 'test-features-read-by-name',
				'group'       => 'test-group',
				'description' => 'Test description',
				'values'      => array( 'key' => 'value' ),
				'default'     => array( 'key' => 'default' ),
			)
		);

		$original_id = $original_settings->get_id();

		// Create a new features object to read data into
		$settings_to_read = new Settings_Model();
		$settings_to_read->set_name( 'test-features-read-by-name' );

		// Read data
		$this->store->read( $settings_to_read );

		// Verify data was properly read
		$this->assertEquals( $original_id, $settings_to_read->get_id() );
		$this->assertEquals( 'test-features-read-by-name', $settings_to_read->get_name() );
		$this->assertEquals( 'test-group', $settings_to_read->get_group() );
		$this->assertEquals( 'Test description', $settings_to_read->get_description() );
		$this->assertEquals( array( 'key' => 'value' ), $settings_to_read->get_values() );
	}

	/**
	 * Test read method handles non-existent features
	 */
	public function test_read_handles_non_existent_settings() {
		// Create a new features instance with non-existent ID
		$settings = new Settings_Model( 999 );

		// Verify object was marked as read despite not existing
		$this->assertTrue( $settings->get_object_read() );

		// Verify ID remains as set
		$this->assertEquals( 999, $settings->get_id() );

		// Create a new features instance with non-existent name
		$settings = new Settings_Model( 'non-existent-setting' );

		// Verify object was marked as read despite not existing
		$this->assertTrue( $settings->get_object_read() );

		// Verify name remains as set
		$this->assertEquals( 'non-existent-setting', $settings->get_name() );
	}

	/**
	 * Test update method updates features data
	 */
	public function test_update_updates_settings_data() {
		// Create features
		$settings = $this->create_settings(
			array(
				'name'        => 'test-update-features',
				'group'       => 'test-group',
				'description' => 'Original description',
				'values'      => array( 'key' => 'original' ),
			)
		);

		$settings_id = $settings->get_id();

		// Update features
		$settings->set_description( 'An updated setting' );
		$settings->set_values( array( 'key' => 'updated' ) );

		// Store the update
		$this->store->update( $settings );

		// Get fresh copy from store
		$updated_settings = new Settings_Model( $settings_id );
		$this->store->read( $updated_settings );

		// Verify updates were applied
		$this->assertEquals( 'An updated setting', $updated_settings->get_description() );
		$this->assertEquals( array( 'key' => 'updated' ), $updated_settings->get_values() );
	}

	/**
	 * Test update method handles non-existent features
	 */
	public function test_update_handles_non_existent_settings() {
		// Create a features without saving it
		$settings = new Settings_Model();
		$settings->set_id( 999 );
		$settings->set_name( 'test-setting' );

		// Update the features
		$this->store->update( $settings );

		// No assertion needed, just verifying it doesn't error
	}

	/**
	 * Test update method updates the date_modified even when no specific property changes made
	 */
	public function test_update_updates_date_even_when_no_property_changes() {
		// Create features
		$settings = $this->create_settings(
			array(
				'group' => 'test-group',
				'name'  => 'test-setting',
			)
		);

		// Store the last modified date
		$original_modified = $settings->get_date_modified();

		// Wait a moment to ensure timestamp changes
		sleep( 1 );

		// Update without changes
		$this->store->update( $settings );

		// Verify modified date was updated despite no property changes
		// This confirms our modification to force updates works correctly
		$this->assertGreaterThan( $original_modified, $settings->get_date_modified() );
	}

	/**
	 * Test delete method deletes features
	 */
	public function test_delete_deletes_settings() {
		// Create 3 test features
		$settings1 = $this->create_settings(['name' => 'delete-test-1']);
		$settings2 = $this->create_settings(['name' => 'delete-test-2']);
		$settings3 = $this->create_settings(['name' => 'delete-test-3']);

		// Delete second features
		$this->store->delete( $settings2 );

		// Get all features
		$all_settings = $this->store->get_all();

		// Verify features was removed
		$found = false;
		foreach ($all_settings as $settings) {
			if ($settings->get_name() === 'delete-test-2') {
				$found = true;
				break;
			}
		}

		$this->assertFalse($found, 'Deleted features should not be in get_all results');
		$this->assertCount(2, $all_settings, 'There should be 2 features after deletion');
	}

	/**
	 * Test delete method handles non-existent features
	 */
	public function test_delete_handles_non_existent_settings() {
		// Create a features without saving it
		$settings = new Settings_Model();
		$settings->set_id( 999 );

		// Delete the features
		$this->store->delete( $settings );

		// No assertion needed, just verifying it doesn't error
	}

	/**
	 * Test read_meta method handles features meta data
	 */
	public function test_read_meta_handles_settings_meta() {
		// This should return an empty array for non-existent features
		$settings = new Settings_Model();
		$settings->set_id( 999 );

		$meta = $this->store->read_meta( $settings );
		$this->assertEmpty( $meta );

		// For real implementation, we would need to test with actual meta data
		// but the mock doesn't support this fully
	}

	/**
	 * Test get_all returns all features
	 */
	public function test_get_all_returns_all_settings() {
		// Clear data store
		$this->store->clear();

		// Add 3 test features
		$settings1 = $this->create_settings(['name' => 'get-all-test-1', 'group' => 'group1']);
		$settings2 = $this->create_settings(['name' => 'get-all-test-2', 'group' => 'group1']);
		$settings3 = $this->create_settings(['name' => 'get-all-test-3', 'group' => 'group2']);

		// Get all features
		$all_settings = $this->store->get_all();

		// Should return exactly 3 features
		$this->assertCount(3, $all_settings, "There should be 3 features total");

		// Get features from group1
		$group1_settings = $this->store->get_all( 'group1' );

		// Should return exactly 2 features
		$this->assertCount(2, $group1_settings, "There should be 2 features in group1");

		// Get features from group2
		$group2_settings = $this->store->get_all( 'group2' );

		// Should return exactly 1 setting
		$this->assertCount(1, $group2_settings, "There should be 1 setting in group2");
	}

	/**
	 * Test get_by_group method returns features by group
	 */
	public function test_get_by_group_returns_settings_by_group() {
		// Create features in group1
		$this->create_settings_multiple( 2, array( 'group' => 'group1' ) );

		// Create features in group2
		$this->create_settings_multiple( 3, array( 'group' => 'group2' ) );

		// Get features by group
		$group1_settings = $this->store->get_by_name( 'group1' );
		$group2_settings = $this->store->get_by_name( 'group2' );

		// Verify counts
		$this->assertCount( 2, $group1_settings );
		$this->assertCount( 3, $group2_settings );

		// Verify all group1 features have the correct group
		foreach ( $group1_settings as $setting ) {
			$this->assertEquals( 'group1', $setting->get_group() );
		}

		// Verify all group2 features have the correct group
		foreach ( $group2_settings as $setting ) {
			$this->assertEquals( 'group2', $setting->get_group() );
		}
	}

	/**
	 * Test clear_caches method clears features caches
	 */
	public function test_clear_caches_clears_settings_caches() {
		// Create features
		$settings = $this->create_settings(
			array(
				'group' => 'test-group',
				'name'  => 'test-setting',
			)
		);

		// This is difficult to test directly with the mock store,
		// but we can at least ensure the method runs without errors
		$this->store->clear_caches( $settings );

		// No assertion needed, just verifying it doesn't error
	}

	/**
	 * Test create_tables method creates tables
	 */
	public function test_create_tables_creates_tables() {
		// Call create_tables
		$this->store->create_tables();

		// Verify tables created flag is set
		$this->assertTrue( $this->store->tables_created );
	}

	/**
	 * Test the get_by_id method
	 */
	public function test_get_by_id() {
		$this->create_test_settings();

		// Test getting features by ID
		$id = 1; // First setting ID
		$settings = $this->store->get_by_id( $id );

		$this->assertInstanceOf( Settings_Model::class, $settings );
		$this->assertEquals( $id, $settings->get_id() );
		$this->assertEquals( 'test-setting-1', $settings->get_name() );
		$this->assertEquals( 'test-group', $settings->get_group() );
	}

	/**
	 * Test the get_by_name method
	 */
	public function test_get_by_name() {
		$this->create_test_settings();

		// Test getting features by name
		$name = 'test-setting-2';
		$settings = $this->store->get_by_name( $name );

		$this->assertInstanceOf( Settings_Model::class, $settings );
		$this->assertEquals( 2, $settings->get_id() ); // Should be the second one
		$this->assertEquals( $name, $settings->get_name() );
		$this->assertEquals( 'test-group', $settings->get_group() );
	}
}
