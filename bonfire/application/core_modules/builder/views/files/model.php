<?php
$model = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class '.ucfirst($controller_name).'_model extends BF_Model {

	protected $table		= "'.$table_name.'";
	protected $key			= "'.$primary_key_field.'";
	protected $soft_deletes	= '.$this->input->post('use_soft_deletes').';
	protected $date_format	= "datetime";
	protected $set_created	= '.$this->input->post('use_created').';
	protected $set_modified = '.$this->input->post('use_modified').';';
	
	// use the created field? Add field and custom name if chosen.
	if ($this->input->post('use_created') == 'true')
	{
		$model .= '
	protected $created_field = "'.$this->input->post('created_field').'";';	
	}
	
	// use the created field? Add field and custom name if chosen.
	if ($this->input->post('use_modified') == 'true')
	{
		$model .= '
	protected $modified_field = "'.$this->input->post('modified_field').'";';		
	}	
	
// Enhanced Parent-Child Builder - Add column definitions
	$pk = $this->input->post( "primary_key_field" );
	$columns = "'{$pk}' => array( 'name' => '{$pk}', 'label' => '" . ucwords(str_replace( '_', ' ', $pk ) ) . "' )";
	$atts = array( 'name', 'label', 'reference' );
	for ( $counter = 0; $counter <= $field_total; $counter++ ) {
		if ( $colname = $this->input->post( "view_field_name{$counter}" ) )
		{
			$colatts = '';
			foreach ( $atts as $att ) $colatts .= ", '{$att}' => '" . $this->input->post( "view_field_{$att}{$counter}" ) . "'";
			$colatts = ltrim( $colatts, ",\t\n\r " );
			$columns .= "
								,'{$colname}' => array( {$colatts} )";
		}
	}
	$columns = ltrim( $columns, ",\t\n\r" );
	$model .= "

	// Columns
	protected \$columns = array( {$columns}
							);";
// Enhanced Parent-Child Builder - end of Add column definitions

// Enhanced Parent-Child Builder - Add child tables
	$children = str_replace( "\n", ',', trim( $this->input->post( 'primary_key_children' ), "\n\r,. " ) );
	$childtables = array();
	$childvalue = '';
	if ( !empty( $children ) )
	{
		foreach ( explode( ",", strtolower( $children ) ) as $child )
		{
			$child = trim( $child, "\n\r,. " );
			$f = explode( '.', trim( $child, "\n\r,. " ) );
			if ( count( $f ) < 2 ) break;
			if ( count( $f ) == 2 ) $f[] = ucwords( $f[0] );
			if ( !isset( $childtables[ $f[0] ] ) ) $childtables[ $f[0] ] = array();
			$childtables[ $f[0] ][] = $child;
			$childvalue .= "
								,'{$f[0]}.{$f[1]}' => array( 'table' => '{$f[0]}', 'column' => '{$f[1]}', 'label' => '{$f[2]}' )";
		}
		if ( empty( $childvalue ) ) $childvalue = '';
		else $childvalue = trim( $childvalue, ",\t\n\r" );
	}
	$model .= "

	// Known Children
	protected \$children	= array( {$childvalue}
							);";
// Enhanced Parent-Child Builder - end of Add child tables

// Enhanced Parent-Child Builder - Add References to selection & build check_nulls content
	$selects = $joins = $functions = '';
	$reftables = array();
	for ( $counter = 0; $counter <= $field_total; $counter++ ) {
		if ( $ref = $this->input->post( "view_field_reference{$counter}" ) and $this->input->post( "view_field_label{$counter}" ) and $this->input->post( "view_field_name{$counter}" ) ) {
			$refparts = explode( '.', $ref );
			if ( !array_key_exists( $refparts[0], $reftables ) ) $reftables[ $refparts[0] ] = '';
			$colname  = $this->input->post( "view_field_name{$counter}" );
			$selects .= ', ' . $refparts[0] . $reftables[$refparts[0]] . '.' . $refparts[2] . ' as ' . $refparts[0] . $reftables[$refparts[0]] . '_' . $refparts[2];
			$joins   .= "\n\t\t\$this->db->join( '{$refparts[0]} {$refparts[0]}{$reftables[$refparts[0]]}', '{$refparts[0]}{$reftables[$refparts[0]]}.{$refparts[1]} = {$table_name}.{$colname}', 'left outer' );";
			if ( '' == $reftables[ $refparts[0] ] ) $reftables[ $refparts[0] ] = 2;
			else $reftables[ $refparts[0] ] += 1;
			$where = "'{$refparts[1]}' => \$where";
			if ( isset( $childtables[ $refparts[0] ] ) )
			{
				$where = '';
				foreach ( $childtables[ $refparts[0] ] as $t )
				{
					$t = explode( '.', $t );
					$where .= ", '{$t[1]}' => \$where";
				}
				$where = substr( $where, 2 );
			}
			$functions .= "

	public function {$colname}_format_dropdown( \$where = NULL, \$withnull = FALSE )
	{
		if ( !is_array( \$where ) )
		{
			if ( is_numeric( \$where ) ) \$where = array( '{$refparts[1]}' => \$where );
			elseif ( 1 == count( func_get_args() ) )
			{
				\$withnull = \$where;
				\$where = NULL;
			}
		}
		\$this->load->model( '{$refparts[0]}/{$refparts[0]}_model', NULL, TRUE );
		\${$refparts[0]} = new " . ucwords( $refparts[0] ) . "_Model();
		if ( is_array( \$where ) ) \${$refparts[0]}->db->where( \$where );
		\$dropdown = \${$refparts[0]}->format_dropdown( '{$refparts[2]}' );
		if ( !\$withnull ) return \$dropdown;
		\$return = array( 'null' => ' ' );
		foreach ( \$dropdown as \$key => \$value ) \$return[ \$key ] = \$value;
		return \$return;
	}";
		}
		if ( $v = $this->input->post( "validation_rules{$counter}" ) and $this->input->post( "view_field_label{$counter}" ) and $this->input->post( "view_field_name{$counter}" ) ) {
			$v = array_flip( $v );
			if ( isset( $v['nullable'] ) ) $field_name = set_value( "view_field_name{$counter}" );
		}
	}
	if ( !empty( $selects ) ) {
		$model .= "

	protected \$before_find = array( 'before_find' );

	protected function before_find()
	{
		\$this->selects = '{$table_name}.*{$selects}';{$joins}
	}{$functions}
";

	}

	$model .= '

	public function get_columns() { return $this->columns; }

	public function get_children() { return $this->children; }
';

// Enhanced Parent-Child Builder - End of References	
	$model .= 
'
}
';

echo $model;
?>
