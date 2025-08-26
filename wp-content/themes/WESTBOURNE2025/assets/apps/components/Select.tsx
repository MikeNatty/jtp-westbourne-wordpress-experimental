import React, { RefObject, useRef, useImperativeHandle, forwardRef } from 'react';

import Choices from 'choices.js';
// import ChoicesUtils from '../../scripts/ChoicesUtils';

// type SelectProps<T extends string> = {
//   onChange: (value: T) => void;
//   id: string;
//   value: string;
//   ariaLabel: string;
//   label: string;
//   options: { value: T; label: string }[];
//   ref?: React.Ref<HTMLSelectElement>;
// };

// function Select<T extends string>({
//   onChange,
//   id,
//   value,
//   ariaLabel = 'Select',
//   label,
//   options,
//   ref,
// }: SelectProps<T>) {

type SelectProps<T extends string> = {
  onChange: (value: T) => void;
  id: string;
  value: string;
  ariaLabel: string;
  label: string;
  select?: React.Ref<HTMLSelectElement>;
  choice?: React.Ref<Choices | null>;
  options: { value: T; label: string }[];
};

const Select = forwardRef<HTMLSelectElement, SelectProps<string>>((props, ref) => {
  const { onChange, id, value, ariaLabel, label, options } = props;
  const selectRef = useRef<HTMLSelectElement>(null);
  const choiceRef = useRef<Choices | null>(null);

  useImperativeHandle(ref, () => ({
    select: selectRef,
    choice: choiceRef,
    reset: () => reset(),
  }));

  // Create Choices from Select elements
  React.useEffect(() => {

    if (!selectRef.current) return;

    const config = ChoicesUtils.getDefaultConfig( selectRef.current )
    // const choice = new Choices( selectRef.current, config );
    choiceRef.current = new Choices( selectRef.current, config );

    return () => {
      // TODO :: Mike - this is causing errors in console, disabling for now. Needs investigation.
      // selectRef.destroy();
    };

  }, []);

  /**
   * NONE OF THESE OPTIONS ARE WORKING TO RESET THE CHOICES SELECT ELEMENT :(
   */
  const reset = () => {
    // We cant do anything unless the placeholder option is enabled
    enablePlaceholder( true )
    const choice = choiceRef.current;
    const select = selectRef.current;

    choice?.destroy()
    enablePlaceholder( true )
    choice?.init()
    choice?.setChoiceByValue('' )
    enablePlaceholder( false )


  }

  /**
   * Enable or disable the placeholder option in both the source select and the associated Choices object.
   * This code works but does not enable resetting the label in the Choices component (as far as I've tested - Mike).
   * @param disabled
   */
  const enablePlaceholder = ( enable=true ) => {
    const select = selectRef.current;
    const choice = choiceRef.current;
    if (!select) return;

    for (const option of select.options) {
        option.disabled = !enable;
    }

    let list = choice?.choiceList?.element;
    if( enable ){

      for (const child of  list?.children || []) {
        if( child.classList.contains('choices__placeholder')) {
          child.classList.remove('choices__item--disabled')
          child.classList.add('choices__item--selectable')
          child.setAttribute( 'data-choice-disabled', 'false');
          child.setAttribute( 'data-disabled', 'false');
          child.setAttribute( 'aria-disabled', 'false');
          console.log(child)
        }

      }
    } else {
        for (const child of list?.children || []) {
          if( child.classList.contains('choices__placeholder')) {
          child.classList.add('choices__item--disabled')
          child.classList.remove('choices__item--selectable')
          child.setAttribute('data-choice-disabled', 'true');
          child.setAttribute('data-disabled', 'true');
          child.setAttribute('aria-disabled', 'true');
          console.log(child)
        }
      }
    }


  }

  const handleChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    const selectedValue = options.find(
      (opt) => opt.value === e.target.value
    )?.value;

    if (selectedValue) {
      onChange(selectedValue);
    }
  };

  return (
    <select
      id={id}
      value={value}
      onChange={handleChange}
      aria-label={ariaLabel}
      ref={selectRef}
    >
      <option value="" disabled>
        {label}
      </option>
      {options.map(({ value: optionValue, label }) => (
        <option key={optionValue} value={optionValue}>
          {label}
        </option>
      ))}
    </select>
  );
});

export default Select;
