ALTER TABLE `player`
ADD COLUMN `player_width`  int NULL DEFAULT -1 COMMENT '播放器宽度' AFTER `background`,
ADD COLUMN `cover_width`  int NULL DEFAULT -1 COMMENT '封面图宽度' AFTER `player_width`,
ADD COLUMN `show_notes`  int(1) NULL DEFAULT 1 COMMENT '显示音符：0不显示1显示' AFTER `cover-width`,
ADD COLUMN `auto_popup_player`  int NULL DEFAULT -1 COMMENT '几秒后弹出音符：-1不弹出 >0秒后弹出' AFTER `show_notes`;