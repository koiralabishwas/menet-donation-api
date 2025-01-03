export default function Header() {
    return (
        <header className="flex items-center h-16 bg-white border-b xl:h-24 border-neutral-light dark:bg-neutral">
            <img
                src="/svg/logo.svg"
                alt="ME-net Logo"
                className="w-auto h-full dark:hidden"
            />
            <img
                src="/svg/logo_dark.svg"
                alt="ME-net Logo"
                className="hidden w-auto h-full dark:block"
            />
        </header>
    );
}
