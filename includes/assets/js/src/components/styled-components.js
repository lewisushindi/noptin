import { Button, __experimentalText as Text } from "@wordpress/components";
import styled from '@emotion/styled';
import { css } from '@emotion/react';

/**
 * Displays a block button.
 *
 * @param {Object} props
 * @returns {styled} The block button.
 */
export const BlockButton = styled( Button, { shouldForwardProp: prop => prop !== '__withNoMargin' })`
	width: 100%;
	justify-content: center;
	font-size: 14px;
	min-height: 50px;
    margin: ${props => (props.__withNoMargin ? '0' : '1.6rem 0')};
`

/**
 * Displays an error Notice.
 */
export const ErrorNotice = styled.div`
    background: #fff;
    border-left: 4px solid #dc3232;
    border-radius: 0.25rem;
    color: #dc3232;
    margin: 0.6rem 0;
    padding: 0.6rem;
    width: 100%;
`

/**
 * Displays a heading text.
 */
export const HeadingText = styled( Text )`
    margin-bottom: 1.6rem;
    font-weight: 600;
    font-size: 20px;
`

/**
 * Wraps a progressbar.
 */
const ProgressBarWrapper = styled.div`
    width: 100%;
    height: 20px;
    background: #eee;
    margin: 1.6rem 0;
    border-radius: 0.25rem;
    max-width: 600px;
    overflow: hidden;
`

/**
 * Renders the progressbar child.
 */
const progressbarWidthStyle = ({ total, processed }) => {
    const width = total === processed ? '100%' : ( processed ? `${ ( processed / total ) * 100 }%` : '0%' );

    return css`width: ${width};`;
}

/**
 * Renders the progressbar child.
 */
const ProgressBarInner = styled.div`
    ${progressbarWidthStyle};
    height: 100%;
    transition: width 0.5s ease-in-out;
    animation: position 3s linear infinite;
    position: relative;
    border-radius: 0.25rem;

    @keyframes position {
        0% {
            left: 0;
            right: 100%;
            background: #72aee6;
        }
        100% {
            right: 0;
            left: 100%;
            background: #007cba;
        }
    }
`

/**
 * Displays a progress bar.
 *
 * @param {Object} props
 * @param {Number} props.total - The total number of records.
 * @param {Number} props.processed - The number of records processed.
 * @returns {JSX.Element} The progress bar.
 */
export const ProgressBar = ( { total, processed } ) => {

    return (
        <ProgressBarWrapper>
            <ProgressBarInner total={ total } processed={ processed } />
        </ProgressBarWrapper>
    );
};
