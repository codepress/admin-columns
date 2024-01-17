// @ts-nocheck

import {registerSettingType} from "../helper";
import LabelSetting from "../components/settings/LabelSetting.svelte";
import WidthSetting from "../components/settings/WidthSetting.svelte";
import EmptySetting from "../components/settings/EmptySetting.svelte";
import TypeSetting from "../components/settings/TypeSetting.svelte";
import ToggleSetting from "../components/settings/ToggleSetting.svelte";
import TextSetting from "../components/settings/TextSetting.svelte";
import MessageSetting from "../components/settings/MessageSetting.svelte";
import NumberSetting from "../components/settings/Number.svelte";
import NumberPreviewSetting from "../components/settings/NumberPreviewSetting.svelte";
import SelectSetting from "../components/settings/SelectSetting.svelte";
import DateFormatSetting from "../components/settings/DateFormatSetting.svelte";
import HiddenSetting from "../components/settings/HiddenSetting.svelte";


registerSettingType('label', LabelSetting)
registerSettingType('width', WidthSetting)
registerSettingType('empty', EmptySetting)
registerSettingType('type', TypeSetting)
registerSettingType('toggle', ToggleSetting)
registerSettingType('text', TextSetting)
registerSettingType('message', MessageSetting)
registerSettingType('number', NumberSetting)
registerSettingType('number_preview', NumberPreviewSetting)
registerSettingType('select', SelectSetting)
registerSettingType('date_format', DateFormatSetting)
registerSettingType('hidden', HiddenSetting)