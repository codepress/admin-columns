// @ts-nocheck

import {registerSettingType} from "../helper";
import LabelInput from "../components/settings/input/LabelInput.svelte";
import WidthInput from "../components/settings/input/WidthInput.svelte";
import ToggleInput from "../components/settings/input/ToggleInput.svelte";
import TextInput from "../components/settings/input/TextInput.svelte";
import MessageSetting from "../components/settings/MessageSetting.svelte";
import NumberInput from "../components/settings/input/NumberInput.svelte";
import NumberPreviewInput from "../components/settings/input/NumberPreviewInput.svelte";
import SelectInput from "../components/settings/input/SelectInput.svelte";
import DateFormatSetting from "../components/settings/input/DateFormatInput.svelte";
import HiddenInput from "../components/settings/input/HiddenInput.svelte";
import SelectRemoteInput from "../components/settings/input/SelectRemoteInput.svelte";
import SelectOptionsInput from "../components/settings/input/SelectOptionsInput.svelte";
import SelectMultipleInput from "../components/settings/input/SelectMultipleInput.svelte";


registerSettingType('label', LabelInput)
registerSettingType('width', WidthInput)
registerSettingType('toggle', ToggleInput)
registerSettingType('text', TextInput)
registerSettingType('message', MessageSetting)
registerSettingType('number', NumberInput)
registerSettingType('number_preview', NumberPreviewInput)
registerSettingType('select', SelectInput)
registerSettingType('date_format', DateFormatSetting)
registerSettingType('hidden', HiddenInput)
registerSettingType('select_remote', SelectRemoteInput)
registerSettingType('select_multiple', SelectMultipleInput)
registerSettingType('select_options', SelectOptionsInput)