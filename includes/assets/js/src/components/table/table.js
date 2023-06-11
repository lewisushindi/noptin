/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { CheckboxControl } from "@wordpress/components";
import { useMemo } from "@wordpress/element";

/**
 * Internal dependencies
 */
import BodyCell from './body-cell';
import HeaderCell from './header-cell';
import { ScrollableTable, TableRow, TableCellNoData } from '../styled-components';

const ASC = 'asc';
const DESC = 'desc';

/**
 * Calculates a row key.
 *
 * @param {Object} row The row.
 * @param {int} index The row index.
 * @param {string} idProp The ID prop.
 */
const getRowKey = ( row, index, idProp ) => {

	// If the row has an ID, use that.
	if ( idProp && row[idProp] ) {
		return row[idProp];
	}

	// If the row has an ID, use that.
	if ( row.id ) {
		return row.id;
	}

	// If the row has a key, use that.
	if ( row.key ) {
		return row.key;
	}

	// Otherwise, use the index.
	return index;
}

/**
 * Displays a table header.
 *
 * @param {Object} props Component props.
 */
export const TableHeader = ( { headers, hasData, sortBy, sortDir, onQueryChange, rows, idProp, canSelectRows, selected, setSelected } ) => {

	// Loop through all rows and retrieve an array of keys.
	const rowKeys = useMemo( () => rows.map( ( row, index ) => getRowKey( row, index, idProp ) ), [ rows, idProp ] );
	const isAllSelected = selected.length > 0 && selected.length === rowKeys.length;

	// Maybe change the sort direction.
	const setSortBy = ( col ) => {

		// Maybe change the sort direction.
		if ( col === sortBy ) {
			onQueryChange( { order: sortDir === ASC ? DESC : ASC } );
		} else {
			onQueryChange( { orderby: col } );
		}
	};

	return (
		<thead>
			<TableRow className="noptin-table__row">

				{ canSelectRows && (
					<HeaderCell
						columnLabel={ __( 'Toggle selection', 'newsletter-optin-box' ) }
						columnKey="cb"
						align="center"
						minWidth="20px"
						display={
							<CheckboxControl
								checked={ isAllSelected }
								onChange={ () => setSelected( isAllSelected ? [] : rowKeys ) }
								__nextHasNoMarginBottom
							/>
						}
						cellClassName="noptin-table-column__cb"
					/>
				) }

				{ headers.map( ( header ) => {

					const { key, label, isSortable, ...props } = header;
					return (
						<HeaderCell
							key={ key }
							columnLabel={ label }
							columnKey={ key }
							isSortable={ isSortable && hasData }
							isSorted={ sortBy === key }
							sortDir={ sortDir }
							onClick={ () => setSortBy( key ) }
							cellClassName={ 'noptin-table-column__' + key.replace( /_/g, '-' ) }
							{ ...props }
						/>
					);

				} ) }
			</TableRow>
		</thead>
	);
}

/**
 * A table component, without the Card wrapper.
 *
 * @param {Object} props Component props.
 * @param {Array} props.headers Array of objects with column-related properties.
 * @param {Array} props.rows Array of objects, where each array is a row in the table.
 * @param {string} props.caption Caption for the table.
 * @param {string} props.emptyMessage Message to display when there are no rows.
 * @param {string} props.query.sortBy Column key to sort by.
 * @param {string} props.query.sortDir Sort direction.
 * @param {Function} props.DisplayCell Callback function to display a single cell.
 * @param {Function} props.onQueryChange Callback function to change the query.
 * @param {string} props.idProp The property to use as the row key.
 */
const Table = ( {
	query,
	headers = [],
	rows = [],
	caption,
	emptyMessage,
	onQueryChange,
	DisplayCell,
	canSelectRows,
	selected = [],
	setSelected = () => {},
	idProp = 'id',
	...extraProps
} ) => {
	const sortBy = query.orderby || 'id';
	const sortDir = query.order || DESC;

	const hasData = !! rows.length;

	return (
		<ScrollableTable
			tabIndex="0"
			aria-label={ `${ caption } - ${ __( '(scroll to see more)', 'newsletter-optin-box' ) }` }
			role="group"
			{...extraProps}
		>
			<table>

				<TableHeader
					headers={headers}
					hasData={hasData}
					sortBy={sortBy}
					sortDir={sortDir}
					onQueryChange={onQueryChange}
					canSelectRows={canSelectRows}
					selected={selected}
					setSelected={setSelected}
					rows={rows}
					idProp={idProp}
				/>

				<tbody>

					{ hasData ? (
						rows.map( ( row, i ) => {
							const rowKey = getRowKey( row, i, idProp );

							return (
								<TableRow className="noptin-table__row" key={ rowKey }>

									{canSelectRows && (
										<BodyCell
											headerKey={ 'cb' }
											row={ row }
											header={ { key: 'select', align: 'center', minWidth: '20px', } }
											cellClassName={ 'noptin-table__col-cb' }
											DisplayCell={ () => (
												<CheckboxControl
													checked={ selected.includes( rowKey ) }
													onChange={ () => {
														if ( selected.includes( rowKey ) ) {
															setSelected( selected.filter( ( item ) => item !== rowKey ) );
														} else {
															setSelected( [ ...selected, rowKey ] );
														}
													} }
													__nextHasNoMarginBottom
												/>
											) }
										/>
									)}

									{ headers.map( ( { key, ...header} ) => (
										<BodyCell
											key={ `${rowKey}__${key}` }
											headerKey={ key }
											row={ row }
											header={ header }
											isSorted={ sortBy === key }
											cellClassName={ 'noptin-table__col-' + key?.replace( /_/g, '-' ) }
											DisplayCell={ DisplayCell }
										/>
									) ) }
								</TableRow>
							);
 						} )
					) : (
						<TableRow className="noptin-table__row">
							<TableCellNoData colSpan={ headers.length }>
								{ emptyMessage ?? __( 'No data to display', 'newsletter-optin-box' ) }
							</TableCellNoData>
						</TableRow>
					) }
				</tbody>
			</table>
		</ScrollableTable>
	);
};

export default Table;
