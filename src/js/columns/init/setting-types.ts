import {registerSettingType} from "../helper";
import LabelSetting from "../components/settings/LabelSetting.svelte";
import {SvelteComponent} from "svelte";
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

registerSettingType('label', LabelSetting as typeof SvelteComponent)
registerSettingType('width', WidthSetting as typeof SvelteComponent)
registerSettingType('empty', EmptySetting as typeof SvelteComponent)
registerSettingType('type', TypeSetting as typeof SvelteComponent)
registerSettingType('toggle', ToggleSetting as typeof SvelteComponent)
registerSettingType('text', TextSetting as typeof SvelteComponent)
registerSettingType('message', MessageSetting as typeof SvelteComponent)
registerSettingType('number', NumberSetting as typeof SvelteComponent)
registerSettingType('number_preview', NumberPreviewSetting as typeof SvelteComponent)
registerSettingType('select', SelectSetting as typeof SvelteComponent)
registerSettingType('date_format', DateFormatSetting as typeof SvelteComponent)
registerSettingType('hidden', HiddenSetting as typeof SvelteComponent)