( function ( blocks, element, serverSideRender, blockEditor ) {
    var el = element.createElement,
        registerBlockType = blocks.registerBlockType,
        ServerSideRender = serverSideRender,
        useBlockProps = blockEditor.useBlockProps;
 
    registerBlockType( 'sheet-music-library/download-box', {
 
        edit: function ( props ) {
            var blockProps = useBlockProps();

			
			return el(
                'div',
                blockProps,
                el( ServerSideRender, {
                    block: 'sheet-music-library/download-box',
                    attributes: props.attributes,
                } )
            );
		}
    } );
} )(
    window.wp.blocks,
    window.wp.element,
    window.wp.serverSideRender,
    window.wp.blockEditor
);