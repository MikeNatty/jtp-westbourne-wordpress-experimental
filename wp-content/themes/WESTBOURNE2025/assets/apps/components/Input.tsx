import React from 'react';

type InputProps = {
  value: string;
  onChange: (value: string) => void;
  placeholder: string;
  ariaLabel: string;
  search: boolean;
};

function Input({
  value,
  onChange,
  placeholder,
  ariaLabel,
  search,
}: InputProps) {
  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    e.preventDefault();
    const newValue = e.target.value;
    onChange(newValue);
  };

  return (
    <input
      type={search ? 'search' : 'text'}
      placeholder={placeholder}
      value={value}
      onChange={handleChange}
      aria-label={ariaLabel}
    />
  );
}

export default Input;
