<?php
namespace AC\Admin;

abstract class Section {

	/** @var string */
	protected $id;

	/** @var string */
	private $title;

	/** @var string */
	private $description;

	public function __construct( $id, $title, $description ) {
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @return void
	 */
	abstract protected function display_fields();

	public function render() {
		?>
		<tr class="<?php echo esc_attr( $this->get_id() ); ?>">
			<th scope="row">
				<h2><?php echo $this->get_title(); ?></h2>
				<p><?php echo $this->get_description(); ?></p>
			</th>
			<td>
				<?php $this->display_fields(); ?>
			</td>
		</tr>
		<?php
	}

}