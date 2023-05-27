/**
 * External dependencies
 */
import { __ } from "@wordpress/i18n";
import { Icon, Path, Circle, SVG } from "@wordpress/components";

/**
 * Returns the app Icon.
 *
 * @param {Object} props Component props.
 */
export default function AppIcon( { size = 24 }) {

	const thePath = 'M303.061 140.185L316.194 139.872C319.415 140.499 321.869 141.692 323.557 143.452C325.244 145.213 325.965 146.726 325.719 147.991C325.495 149.142 324.872 150.811 323.85 153C322.827 155.189 313.808 176.715 296.792 217.578C279.776 258.442 267.22 288.95 259.124 309.104C283.846 286.332 313.041 258.044 346.712 224.238C380.383 190.432 400.645 167.569 407.498 155.65C409.661 151.892 412.048 149.133 414.658 147.372C417.269 145.611 419.47 144.726 421.263 144.717C423.078 144.592 425.768 144.876 429.335 145.569C432.901 146.262 436.943 147.884 441.461 150.433C446.117 152.89 448.098 155.902 447.405 159.468C446.086 166.255 432.219 183.854 405.805 212.266C379.39 240.677 349.709 270.543 316.76 301.863C283.927 333.205 256.768 357.771 235.283 375.561C231.439 378.753 227.274 379.914 222.788 379.042C218.417 378.192 213.903 376.539 209.247 374.082C204.729 371.532 202.85 368.302 203.61 364.39C206.07 351.737 228.988 293.099 272.365 188.477C248.599 209.405 218.871 239.202 183.182 277.868C146.051 317.806 124.583 342.881 118.777 353.094C115.432 359.248 112.336 362.586 109.489 363.107C107.492 363.554 105.458 363.577 103.387 363.174L89.454 362.078C86.8082 361.563 84.5378 360.346 82.6428 358.426C80.6328 356.483 79.7843 354.707 80.0974 353.096C80.9918 348.495 89.9908 335.978 107.094 315.546C124.313 295.136 143.982 273.173 166.102 249.657C188.36 226.048 211.219 203.332 234.682 181.51C258.144 159.687 275.786 145.389 287.609 138.614C290.037 137.176 292.574 136.714 295.22 137.228L303.061 140.185Z';

    const theIcon = (
        <SVG
			className="noptin-app-icon"
			width={size}
			height={size}
			viewBox="0 0 512 512"
			fill="none"
			>
                <Circle cx="256" cy="256" r="256" fill="#E91E63"/>
                <Path d={thePath} fill="white"/>
        </SVG>
    );

    return <Icon size={size} icon={theIcon} />;
};
