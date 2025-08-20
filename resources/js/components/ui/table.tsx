// Converted to Vue-compatible exports (remove React usage)
import { cva, type VariantProps } from 'class-variance-authority';
import { cn } from '@/lib/utils';

const tableVariants = cva(
  'w-full caption-bottom text-sm',
  {
    variants: {
      variant: {
        default: '',
        bordered: 'border-collapse border-spacing-0',
      },
    },
    defaultVariants: {
      variant: 'default',
    },
  }
);

// Simple functional Vue wrappers through render functions
import { h, defineComponent } from 'vue';

export const Table = defineComponent<{ class?: string } & VariantProps<typeof tableVariants>>({
  name: 'UiTable',
  props: { class: String, variant: String as any },
  setup(props, { slots }) {
    return () => h('div', { class: 'w-full overflow-auto' }, [
      h('table', { class: cn(tableVariants({ variant: props.variant as any, className: props.class })) }, slots.default?.())
    ]);
  }
});

export const TableHeader = defineComponent<{ class?: string }>({
  name: 'UiTableHeader',
  props: { class: String },
  setup(props, { slots }) {
    return () => h('thead', { class: cn('[&_tr]:border-b', props.class) }, slots.default?.());
  }
});

export const TableBody = defineComponent<{ class?: string }>({
  name: 'UiTableBody',
  props: { class: String },
  setup(props, { slots }) {
    return () => h('tbody', { class: cn('[&_tr:last-child]:border-0', props.class) }, slots.default?.());
  }
});

export const TableFooter = defineComponent<{ class?: string }>({
  name: 'UiTableFooter',
  props: { class: String },
  setup(props, { slots }) {
    return () => h('tfoot', { class: cn('bg-primary font-medium text-primary-foreground', props.class) }, slots.default?.());
  }
});

export const TableRow = defineComponent<{ class?: string }>({
  name: 'UiTableRow',
  props: { class: String },
  setup(props, { slots }) {
    return () => h('tr', { class: cn('border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted', props.class) }, slots.default?.());
  }
});

export const TableHead = defineComponent<{ class?: string }>({
  name: 'UiTableHead',
  props: { class: String },
  setup(props, { slots }) {
    return () => h('th', { class: cn('h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0', props.class) }, slots.default?.());
  }
});

export const TableCell = defineComponent<{ class?: string }>({
  name: 'UiTableCell',
  props: { class: String },
  setup(props, { slots }) {
    return () => h('td', { class: cn('p-4 align-middle [&:has([role=checkbox])]:pr-0', props.class) }, slots.default?.());
  }
});

export const TableCaption = defineComponent<{ class?: string }>({
  name: 'UiTableCaption',
  props: { class: String },
  setup(props, { slots }) {
    return () => h('caption', { class: cn('mt-4 text-sm text-muted-foreground', props.class) }, slots.default?.());
  }
});

export default Table;
