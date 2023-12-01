const classNames = require('classnames');

// Blocks to add the text-wrap option to
const enableTextWrapButtonOnBlocks = [
  'core/paragraph',
  'core/heading',
  'core/list',
  'core/list-item',
  'core/quote',
  'core/verse',
  'core/post-title',
  'core/post-excerpt',
  'core/post-content',
];

// Add custom attribute to paragraph for text-wrap
function setToolbarButtonAttribute(settings, name) {
  if (!enableTextWrapButtonOnBlocks.includes(name)) {
    return settings;
  }
  return Object.assign({}, settings, {
    attributes: Object.assign({}, settings.attributes, {
      textWrap: {
        type: 'string',
      },
    }),
  });
}
wp.hooks.addFilter('blocks.registerBlockType', 'custom-attributes/text-wrap', setToolbarButtonAttribute);

// Add button to paragraph toolbar to activate/deactivate text-wrap: balance
const el = wp.element.createElement;
const withTextWrapButton = wp.compose.createHigherOrderComponent(BlockEdit => {
  return props => {
    if (!enableTextWrapButtonOnBlocks.includes(props.name)) {
      return el(BlockEdit, { ...props });
    }
    const { attributes, setAttributes } = props;
    const { textWrap } = attributes;
    return el(
      wp.element.Fragment,
      {},
      el(
        wp.blockEditor.BlockControls,
        {
          group: 'inline',
        },
        el(
          wp.components.ToolbarGroup,
          {},
          el(wp.components.ToolbarButton, {
            icon: 'editor-alignleft',
            label: wp.i18n.__('Text Wrap: Balance', 'mdhs'),
            isActive: textWrap === 'balance',
            onClick: () => {
              if (textWrap === 'balance') {
                setAttributes({ textWrap: 'normal' });
              } else {
                setAttributes({ textWrap: 'balance' });
              }
            },
          })
        )
      ),
      el(BlockEdit, { ...props })
    );
  };
}, 'withTextWrapButton');
wp.hooks.addFilter('editor.BlockEdit', 'custom-attributes/with-text-wrap-button', withTextWrapButton);

// Add css class to block wrapper based on attribute
const withTextWrapButtonProp = wp.compose.createHigherOrderComponent(BlockListBlock => {
  return props => {
    // If current block is not allowed
    if (!enableTextWrapButtonOnBlocks.includes(props.name)) {
      return el(BlockListBlock, { ...props });
    }

    const { attributes } = props;
    const { textWrap } = attributes;

    if (textWrap && textWrap === 'balance') {
      return el(BlockListBlock, { className: 'text-wrap-balance', ...props });
    } else {
      return el(BlockListBlock, { ...props });
    }
  };
}, 'withTextWrapButtonProp');
wp.hooks.addFilter('editor.BlockListBlock', 'custom-attributes/with-text-wrap-button-prop', withTextWrapButtonProp);

// Save textWrap attribute to blocks

const saveTextWrapAttribute = (extraProps, blockType, attributes) => {
  // Do nothing if it's another block than our defined ones
  if (enableTextWrapButtonOnBlocks.includes(blockType.name)) {
    const { textWrap } = attributes;
    if (textWrap && textWrap === 'balance') {
      extraProps.className = classNames(extraProps.className, 'text-wrap-balance');
    }
  }
  return extraProps;
};

wp.hooks.addFilter('blocks.getSaveContent.extraProps', 'custom-attributes/save-text-wrap-attribute', saveTextWrapAttribute);
