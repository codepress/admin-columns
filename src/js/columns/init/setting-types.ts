// @ts-nocheck

import {registerSettingType} from "../helper";
import LabelInput from "../components/settings/LabelInput.svelte";
import WidthInput from "../components/settings/WidthInput.svelte";
import EmptySetting from "../components/settings/EmptySetting.svelte";
import ToggleInput from "../components/settings/ToggleInput.svelte";
import TextInput from "../components/settings/TextInput.svelte";
import MessageSetting from "../components/settings/MessageSetting.svelte";
import NumberInput from "../components/settings/NumberInput.svelte";
import NumberPreviewInput from "../components/settings/NumberPreviewInput.svelte";
import SelectInput from "../components/settings/SelectInput.svelte";
import DateFormatSetting from "../components/settings/DateFormatInput.svelte";
import HiddenInput from "../components/settings/HiddenInput.svelte";
import SelectRemoteInput from "../components/settings/SelectRemoteInput.svelte";
import SelectOptionsInput from "../components/settings/SelectOptionsInput.svelte";


registerSettingType('label', LabelInput)
registerSettingType('width', WidthInput)
registerSettingType('empty', EmptySetting)
registerSettingType('toggle', ToggleInput)
registerSettingType('text', TextInput)
registerSettingType('message', MessageSetting)
registerSettingType('number', NumberInput)
registerSettingType('number_preview', NumberPreviewInput)
registerSettingType('select', SelectInput)
registerSettingType('date_format', DateFormatSetting)
registerSettingType('hidden', HiddenInput)
registerSettingType('select_remote', SelectRemoteInput)
registerSettingType('select_options', SelectOptionsInput)