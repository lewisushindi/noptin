/**
 * External dependencies
 */
import { forwardRef, useCallback } from "@wordpress/element";
import { Notice, Spinner, CardBody, TabPanel } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { addQueryArgs } from "@wordpress/url";

/**
 * Local dependencies.
 */
import Wrap from "../wrap";
import { useRecord, useSchema } from "../../../store-data/hooks";
import { useRoute } from "../hooks";
import RecordOverview from "./overview";
import TableTab from "./table-tab";
import { StyledTabPanel } from "../../styled-components";

/**
 * Displays a given tab.
 *
 * @param {Object} props
 * @param {Object} props.tab
 */
const RenderTab = ( { tab } ) => {

	if ( 'table' === tab.type ) {
		return <TableTab tab={ tab } />;
	}

	if ( 'overview' === tab.name ) {
		return <RecordOverview tab={ tab } />;
	}

	return (
		<Wrap title={ tab.title }>
			<p>Tab content</p>
		</Wrap>
    );
}

/**
 * Allows the user to view a single record.
 *
 * @param {Object} props
 * @param {Object} props.component
 */
const UpdateRecord = ( { component: { title } }, ref ) => {

	// Prepare the state.
	const { namespace, collection, args: { hizzle_tab, id } } = useRoute();

	const schema = useSchema( namespace, collection );
	const record = useRecord( namespace, collection, id );
	const tab    = hizzle_tab || 'overview';

	// Fired when a tab is selected.
	const onTabSelect = useCallback( ( tab ) => {

		// Replace the current URL with the new tab.
		const newURL = addQueryArgs( window.location.href, { hizzle_tab: tab } );

		// Navigate to the new URL.
		window.history.replaceState( {}, '', newURL );

	}, [] );

	// Show the loading indicator if we're loading the schema.
	if ( record.isResolving() ) {

		return (
			<Wrap title={ __( 'Loading', 'newsletter-optin-box' ) } ref={ ref }>
				<CardBody>
					<Spinner />
				</CardBody>
			</Wrap>
		);
	}

	// Show error if any.
	if ( record.hasResolutionFailed() ) {
		const error = record.getResolutionError();

		return (
			<Wrap title={ __( 'Error', 'newsletter-optin-box' ) } ref={ ref }>
				<CardBody>
					<Notice status="error" isDismissible={ false }>
						{ error.message || __( 'An unknown error occurred.', 'newsletter-optin-box' ) }
					</Notice>
				</CardBody>
			</Wrap>
		);
	}

	// Prepare the tabs.
	const tabs = [
		{
			title: title,
			name: 'overview',
		}
	]

	// Displays a normal header if there are no tabs.
	if ( ! Array.isArray( schema.data.tabs ) && tabs ) {
		Object.keys( schema.data.tabs ).map( ( tab ) => (
			tabs.push( {
				...schema.data.tabs[tab],
				name: tab,
			} )
		) );
	}

	// Display the update record screen.
	return (
		<div ref={ ref }>

			{ tabs.length === 1 ? (
				<Wrap title={ title }>
					<RenderTab tab={ tabs[0] } />
				</Wrap>
			) : (
				<StyledTabPanel tabs={ tabs } initialTabName={ tab } onSelect={ onTabSelect }>
					{ ( tab ) => <RenderTab tab={ tab } /> }
				</StyledTabPanel>
			) }

		</div>
	);
}

export default forwardRef( UpdateRecord );

// Upsell tags and lists, 
// Overview -> Cards for sent emails, opens, clicks, total spent, avatar, basic info, lists, tags
// Button send email
