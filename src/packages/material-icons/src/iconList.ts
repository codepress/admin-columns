export const icons = [
    'download',
    'swap_vert',
    'edit',
    'stacks',
    'filter_list',
    'filter_alt',
    'help',
    'sentiment_satisfied',
    'star',
    'keep',
    'sell',
    'label'
] as const;

export type IconName = typeof icons[number];