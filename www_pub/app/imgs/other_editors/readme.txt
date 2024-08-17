The icon sprite sheets come in two (2) dimensions, one for standard resolution, one for high-res.

Each sprite sheet is a transparent GIF (indexed transparency) with the icon's background pre-matted against the target background.
The pre-matting is done by using /imgs/font.php.

If an icon is properly matted, the pixelated matting artifact should be visible.

The raw files (icons.png+grid.png, icons_hd.png+grid_hd.png) can be used as layers in image editing programs such as PhotoShop or Gimp.
Place the grid layer on top of the icon layer and set its transparency to 50% (or any other suitable level)
Edit the icon layer while leaving the grid layer intact.

Export only the icon layer. Reduce the color-space to 256 and pick RGB(255,0,0) as the indexed transparent color for the GIF file.

Do not back-port the GIF into the icon layer. The icon layer is the working copy and should only be exported in one direction.
